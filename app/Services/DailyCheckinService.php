<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyCheckin;
use App\Models\CheckinLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class DailyCheckinService
{
    // ==========================================
    // REWARD CONFIGURATION
    // ==========================================
    
    const BASE_REWARD = 10; // Coinkey cơ bản mỗi ngày
    
    // Bonus theo streak
    const STREAK_BONUSES = [
        3 => 5,    // Ngày 3: +5 coinkey
        7 => 20,   // Ngày 7: +20 coinkey
        14 => 50,  // Ngày 14: +50 coinkey
        30 => 200, // Ngày 30: +200 coinkey
    ];

    // Multiplier theo VIP level
    const VIP_MULTIPLIERS = [
        1 => 1.1,  // VIP 1: +10%
        2 => 1.15,
        3 => 1.2,
        4 => 1.25,
        5 => 1.3,
        6 => 1.4,
        7 => 1.5,
        8 => 1.6,
        9 => 1.8,
        10 => 2.0, // VIP 10: x2
    ];

    /**
     * Kiểm tra user có thể điểm danh không
     */
    public function canCheckin(User $user): array
    {
        $checkin = $this->getOrCreateCheckin($user);
        $today = Carbon::today();

        // Đã điểm danh hôm nay
        if ($checkin->last_checkin_date && $checkin->last_checkin_date->isSameDay($today)) {
            return [
                'can_checkin' => false,
                'reason' => 'already_checked_in',
                'next_available' => Carbon::tomorrow()->startOfDay(),
            ];
        }

        return [
            'can_checkin' => true,
            'current_streak' => $checkin->current_streak,
            'estimated_reward' => $this->calculateReward($user, $checkin->current_streak + 1),
        ];
    }

    /**
     * Thực hiện điểm danh
     */
    public function checkin(User $user): array
    {
        $canCheckinResult = $this->canCheckin($user);
        
        if (!$canCheckinResult['can_checkin']) {
            throw new Exception('Bạn đã điểm danh hôm nay rồi!');
        }

        DB::beginTransaction();
        try {
            $checkin = $this->getOrCreateCheckin($user);
            $wallet = $user->getOrCreateWallet();
            
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            // Tính streak
            $isStreakContinued = $checkin->last_checkin_date && 
                                 $checkin->last_checkin_date->isSameDay($yesterday);
            
            $newStreak = $isStreakContinued ? $checkin->current_streak + 1 : 1;
            
            // Tính reward
            $reward = $this->calculateReward($user, $newStreak);
            $bonuses = $this->calculateBonuses($newStreak);
            
            // Cập nhật checkin record
            $checkin->update([
                'current_streak' => $newStreak,
                'longest_streak' => max($checkin->longest_streak, $newStreak),
                'total_checkins' => $checkin->total_checkins + 1,
                'total_earned' => $checkin->total_earned + $reward,
                'last_checkin_date' => $today,
                'last_checkin_at' => now(),
            ]);

            // Thêm coinkey vào ví
            $wallet->deposit(
                amount: $reward,
                type: 'deposit',
                description: "Điểm danh ngày {$newStreak} - Nhận {$reward} Coinkey",
                referenceType: DailyCheckin::class,
                referenceId: $checkin->id
            );

            // Log lại
            CheckinLog::create([
                'user_id' => $user->id,
                'checkin_date' => $today,
                'reward_amount' => $reward,
                'streak_day' => $newStreak,
                'is_bonus' => !empty($bonuses),
                'bonus_type' => $bonuses['type'] ?? null,
                'notes' => $bonuses['message'] ?? null,
            ]);

            DB::commit();

            return [
                'success' => true,
                'reward' => $reward,
                'new_streak' => $newStreak,
                'bonuses' => $bonuses,
                'is_streak_broken' => !$isStreakContinued && $checkin->current_streak > 0,
                'next_milestone' => $this->getNextMilestone($newStreak),
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Tính toán reward
     */
    private function calculateReward(User $user, int streakDay): float
    {
        $wallet = $user->getOrCreateWallet();
        $vipLevel = $wallet->vip_level;

        // Base reward
        $reward = self::BASE_REWARD;

        // Streak bonus
        foreach (self::STREAK_BONUSES as $day => $bonus) {
            if ($streakDay == $day) {
                $reward += $bonus;
                break;
            }
        }

        // VIP multiplier
        $multiplier = self::VIP_MULTIPLIERS[$vipLevel] ?? 1.0;
        $reward *= $multiplier;

        return round($reward, 2);
    }

    /**
     * Tính bonuses đặc biệt
     */
    private function calculateBonuses(int streakDay): array
    {
        $bonuses = [];

        // Streak milestone bonus
        if (isset(self::STREAK_BONUSES[$streakDay])) {
            $bonuses['type'] = 'milestone';
            $bonuses['amount'] = self::STREAK_BONUSES[$streakDay];
            $bonuses['message'] = "🎉 Mốc {$streakDay} ngày! Nhận thưởng đặc biệt!";
        }

        // Weekend bonus (Cuối tuần x1.5)
        if (Carbon::today()->isWeekend()) {
            $bonuses['weekend_multiplier'] = 1.5;
            $bonuses['message'] = ($bonuses['message'] ?? '') . " 🎊 Cuối tuần bonus x1.5!";
        }

        return $bonuses;
    }

    /**
     * Lấy mốc tiếp theo
     */
    private function getNextMilestone(int currentStreak): ?array
    {
        foreach (self::STREAK_BONUSES as $day => $bonus) {
            if ($day > $currentStreak) {
                return [
                    'day' => $day,
                    'bonus' => $bonus,
                    'remaining_days' => $day - $currentStreak,
                ];
            }
        }
        return null;
    }

    /**
     * Lấy hoặc tạo checkin record
     */
    private function getOrCreateCheckin(User $user): DailyCheckin
    {
        return DailyCheckin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'current_streak' => 0,
                'longest_streak' => 0,
                'total_checkins' => 0,
                'total_earned' => 0,
            ]
        );
    }

    /**
     * Lấy thống kê điểm danh
     */
    public function getStats(User $user): array
    {
        $checkin = $this->getOrCreateCheckin($user);
        
        return [
            'current_streak' => $checkin->current_streak,
            'longest_streak' => $checkin->longest_streak,
            'total_checkins' => $checkin->total_checkins,
            'total_earned' => $checkin->total_earned,
            'can_checkin' => $this->canCheckin($user)['can_checkin'],
            'last_checkin' => $checkin->last_checkin_at,
            'next_milestone' => $this->getNextMilestone($checkin->current_streak),
        ];
    }

    /**
     * Lấy lịch sử điểm danh
     */
    public function getHistory(User $user, int $limit = 30): array
    {
        return CheckinLog::where('user_id', $user->id)
            ->orderBy('checkin_date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
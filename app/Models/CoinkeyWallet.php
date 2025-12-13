<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class CoinkeyWallet extends Model
{
    protected $fillable = [
        'user_id', 
        'balance', 
        'total_deposited', 
        'total_spent', 
        'currency', 
        'is_locked', 
        'notes'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_deposited' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'is_locked' => 'boolean',
    ];

    /**
     * Cấu hình các mốc VIP
     * Key: Level, Value: [Mốc nạp, % Giảm giá]
     * Sắp xếp giảm dần để loop check dễ dàng hơn
     */
    const VIP_TIERS = [
        10 => ['threshold' => 100_000_000, 'discount' => 20],
        9  => ['threshold' => 50_000_000,  'discount' => 15],
        8  => ['threshold' => 20_000_000,  'discount' => 12],
        7  => ['threshold' => 10_000_000,  'discount' => 10],
        6  => ['threshold' => 5_000_000,   'discount' => 7],
        5  => ['threshold' => 2_000_000,   'discount' => 5],
        4  => ['threshold' => 1_000_000,   'discount' => 4],
        3  => ['threshold' => 500_000,     'discount' => 3],
        2  => ['threshold' => 200_000,     'discount' => 2],
        1  => ['threshold' => 100_000,     'discount' => 1],
    ];

    // --- Accessors ---

    public function getVipLevelAttribute(): int
    {
        $deposited = $this->total_deposited;

        foreach (self::VIP_TIERS as $level => $data) {
            if ($deposited >= $data['threshold']) {
                return $level;
            }
        }

        return 0;
    }

    public function getDiscountPercentAttribute(): int
    {
        $level = $this->vip_level;
        return self::VIP_TIERS[$level]['discount'] ?? 0;
    }

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CoinkeyTransaction::class, 'wallet_id');
    }

    // --- Helper Methods ---

    public function hasBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    // --- Core Operations (Transaction Safe) ---

    /**
     * Thêm coinkey vào ví (An toàn với DB Transaction)
     */
    public function deposit(float $amount, string $type = 'deposit', ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): CoinkeyTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Số tiền nạp phải lớn hơn 0');
        }

        // Sử dụng DB transaction để đảm bảo tính toàn vẹn
        return DB::transaction(function () use ($amount, $type, $description, $referenceType, $referenceId) {
            // Lock dòng này để tránh xung đột khi update song song
            $wallet = $this->newQuery()->lockForUpdate()->find($this->id);

            $balanceBefore = $wallet->balance;
            
            $wallet->balance += $amount;
            $wallet->total_deposited += $amount;
            $wallet->save();

            // Cập nhật lại instance hiện tại để UI hiển thị đúng ngay lập tức nếu cần
            $this->fill($wallet->getAttributes());

            return $wallet->transactions()->create([
                'user_id'        => $wallet->user_id, // Fix lỗi missing user_id
                'type'           => $type,
                'amount'         => $amount,
                'balance_before' => $balanceBefore,
                'balance_after'  => $wallet->balance,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'description'    => $description ?? "Nạp {$amount} Coinkey",
            ]);
        });
    }

    /**
     * Trừ coinkey từ ví (An toàn với DB Transaction & Lock)
     */
    public function withdraw(float $amount, string $type = 'withdraw', ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): CoinkeyTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Số tiền rút phải lớn hơn 0');
        }

        return DB::transaction(function () use ($amount, $type, $description, $referenceType, $referenceId) {
            // 1. Lock dòng dữ liệu (SELECT ... FOR UPDATE)
            // Quan trọng: Phải lấy instance mới qua lockForUpdate, không dùng $this trực tiếp để check balance
            $wallet = $this->newQuery()->lockForUpdate()->find($this->id);

            // 2. Kiểm tra điều kiện trên dữ liệu mới nhất
            if ($wallet->isLocked()) {
                throw new Exception('Ví hiện đang bị khóa.');
            }

            if ($wallet->balance < $amount) {
                throw new Exception("Số dư không đủ. Hiện có: " . number_format($wallet->balance));
            }

            // 3. Thực hiện trừ tiền
            $balanceBefore = $wallet->balance;
            $wallet->balance -= $amount;
            $wallet->total_spent += $amount;
            $wallet->save();

            // Sync lại data cho object hiện tại
            $this->fill($wallet->getAttributes());

            // 4. Ghi log
            return $wallet->transactions()->create([
                'user_id'        => $wallet->user_id, // Fix lỗi missing user_id
                'type'           => $type,
                'amount'         => -$amount, // Lưu số âm
                'balance_before' => $balanceBefore,
                'balance_after'  => $wallet->balance,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'description'    => $description ?? "Rút {$amount} Coinkey",
            ]);
        });
    }

    /**
     * Hoàn tiền (Wrapper cho deposit với type refund)
     */
    public function refund(float $amount, ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): CoinkeyTransaction 
    {
        return $this->deposit(
            $amount,
            'refund',
            $description ?? "Hoàn tiền {$amount} Coinkey",
            $referenceType,
            $referenceId
        );
    }
}
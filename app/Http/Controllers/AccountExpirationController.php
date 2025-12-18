<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountExpirationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountExpirationController extends Controller
{
    protected AccountExpirationService $expirationService;

    public function __construct(AccountExpirationService $expirationService)
    {
        $this->middleware(['auth', 'admin']);
        $this->expirationService = $expirationService;
    }

    /**
     * Kiểm tra expiration của user
     */
    public function checkExpiration($userId)
    {
        $user = User::findOrFail($userId);
        $daysRemaining = $this->expirationService->getDaysRemaining($user);

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'expires_at' => $user->expires_at?->toIso8601String(),
            'days_remaining' => $daysRemaining,
            'account_status' => $user->account_status,
        ]);
    }

    /**
     * Gia hạn tài khoản (Quick Extend)
     */
    public function extendByDays(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:1|max:3650',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($userId);
            $days = $request->input('days');
            
            $updatedUser = $this->expirationService->extendAccount(
                $user, 
                $days, 
                "Admin extended by {$days} days"
            );

            return response()->json([
                'success' => true,
                'message' => "Account extended by {$days} days successfully",
                'user' => [
                    'id' => $updatedUser->id,
                    'name' => $updatedUser->name,
                    'expires_at' => $updatedUser->expires_at?->toIso8601String(),
                    'days_remaining' => $this->expirationService->getDaysRemaining($updatedUser),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set expiry date cụ thể
     */
    public function setExpiryDate(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'expires_at' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($userId);
            $expiresAt = \Carbon\Carbon::parse($request->expires_at);
            
            $updatedUser = $this->expirationService->setExpiration(
                $user,
                $expiresAt,
                "Admin set expiration to {$expiresAt->format('Y-m-d H:i')}"
            );

            return response()->json([
                'success' => true,
                'message' => 'Expiration date updated successfully',
                'user' => [
                    'id' => $updatedUser->id,
                    'expires_at' => $updatedUser->expires_at->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa expiration (set unlimited)
     */
    public function removeExpiration($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->update([
                'expires_at' => null,
                'account_status' => 'active',
                'account_notes' => ($user->account_notes ?? '') . "\nExpiration removed at " . now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account expiration removed - now unlimited',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suspend account
     */
    public function suspendAccount(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($userId);
            $this->expirationService->suspendAccount($user, $request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Account suspended successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate account
     */
    public function activateAccount($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $this->expirationService->activateAccount($user, "Admin activated account");

            return response()->json([
                'success' => true,
                'message' => 'Account activated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Lấy danh sách users sắp hết hạn
     */
    public function getExpiringSoon(Request $request)
    {
        $days = $request->input('days', 7);
        $users = $this->expirationService->getExpiringAccounts($days);

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'expires_at' => $user->expires_at?->toIso8601String(),
                    'days_remaining' => $this->expirationService->getDaysRemaining($user),
                ];
            }),
        ]);
    }

    /**
     * Lấy danh sách users đã hết hạn
     */
    public function getExpiredAccounts()
    {
        $users = User::where('account_status', 'expired')
            ->orWhere(function($q) {
                $q->where('account_status', 'active')
                  ->whereNotNull('expires_at')
                  ->where('expires_at', '<', now());
            })
            ->orderBy('expires_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'users' => $users,
        ]);
    }
}
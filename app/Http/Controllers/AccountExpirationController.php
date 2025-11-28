<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountExpirationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountExpirationController extends Controller
{
    protected $expirationService;

    public function __construct(AccountExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Gia hạn tài khoản theo số ngày
     */
    public function extendByDays(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:1|max:3650', // Tối đa 10 năm
            'note' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($userId);
        
        $this->expirationService->extendAccount(
            $user, 
            $request->days, 
            $request->note
        );

        return response()->json([
            'success' => true,
            'message' => "Đã gia hạn {$request->days} ngày thành công",
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'expires_at' => $user->expires_at,
                'days_remaining' => $this->expirationService->getDaysRemaining($user),
                'account_status' => $user->account_status
            ]
        ]);
    }

    /**
     * Đặt ngày hết hạn cụ thể
     */
    public function setExpiryDate(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'expiry_date' => 'required|date|after:today',
            'note' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($userId);
        $expiryDate = Carbon::parse($request->expiry_date);
        
        $this->expirationService->setExpirationDate(
            $user, 
            $expiryDate, 
            $request->note
        );

        return response()->json([
            'success' => true,
            'message' => 'Đã đặt ngày hết hạn thành công',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'expires_at' => $user->expires_at,
                'days_remaining' => $this->expirationService->getDaysRemaining($user),
                'account_status' => $user->account_status
            ]
        ]);
    }

    /**
     * Kiểm tra thông tin hết hạn của user
     */
    public function checkExpiration($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'expires_at' => $user->expires_at,
                'is_expired' => $this->expirationService->isExpired($user),
                'days_remaining' => $this->expirationService->getDaysRemaining($user),
                'account_status' => $user->account_status,
                'account_notes' => $user->account_notes
            ]
        ]);
    }

    /**
     * Tạm ngưng tài khoản
     */
    public function suspendAccount(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập lý do tạm ngưng',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($userId);
        
        $this->expirationService->suspendAccount($user, $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'Đã tạm ngưng tài khoản',
            'data' => [
                'user_id' => $user->id,
                'account_status' => $user->account_status,
                'reason' => $request->reason
            ]
        ]);
    }

    /**
     * Kích hoạt lại tài khoản
     */
    public function activateAccount(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $this->expirationService->activateAccount($user, $request->note);

        return response()->json([
            'success' => true,
            'message' => 'Đã kích hoạt lại tài khoản',
            'data' => [
                'user_id' => $user->id,
                'account_status' => $user->account_status,
                'expires_at' => $user->expires_at
            ]
        ]);
    }

    /**
     * Xóa hạn sử dụng (unlimited)
     */
    public function removeExpiration(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $user->expires_at = null;
        $user->account_status = 'active';
        
        if ($request->note) {
            $existingNotes = $user->account_notes ?? '';
            $newNote = Carbon::now()->format('Y-m-d H:i:s') . ": Xóa hạn sử dụng. {$request->note}";
            $user->account_notes = $existingNotes . "\n" . $newNote;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa hạn sử dụng - tài khoản không giới hạn',
            'data' => [
                'user_id' => $user->id,
                'expires_at' => null,
                'account_status' => $user->account_status
            ]
        ]);
    }

    /**
     * Lấy danh sách user sắp hết hạn
     */
    public function getExpiringSoon(Request $request)
    {
        $days = $request->input('days', 7); // Mặc định 7 ngày

        $users = User::where('account_status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', Carbon::now())
            ->where('expires_at', '<=', Carbon::now()->addDays($days))
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'expires_at' => $user->expires_at,
                    'days_remaining' => $this->expirationService->getDaysRemaining($user)
                ];
            });

        return response()->json([
            'success' => true,
            'message' => "Có {$users->count()} tài khoản sẽ hết hạn trong {$days} ngày tới",
            'data' => $users
        ]);
    }

    /**
     * Lấy danh sách user đã hết hạn
     */
    public function getExpiredAccounts()
    {
        $users = User::where('account_status', 'expired')
            ->orWhere(function ($query) {
                $query->where('account_status', 'active')
                      ->whereNotNull('expires_at')
                      ->where('expires_at', '<', Carbon::now());
            })
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'expires_at' => $user->expires_at,
                    'account_status' => $user->account_status,
                    'days_expired' => Carbon::parse($user->expires_at)->diffInDays(Carbon::now())
                ];
            });

        return response()->json([
            'success' => true,
            'message' => "Có {$users->count()} tài khoản đã hết hạn",
            'data' => $users
        ]);
    }
}
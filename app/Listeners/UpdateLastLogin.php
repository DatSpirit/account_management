<?php

namespace App\Listeners;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Login;


class UpdateLastLogin
{
    /**
     * Xử lý sự kiện.
     */
    public function handle(Login $event): void
    {
        
        // Lấy đối tượng User đã đăng nhập
        $user = $event->user;

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'last_login_at' => now(),
                // Tăng giá trị hiện tại lên 1
                'login_count' => DB::raw('coalesce(login_count, 0) + 1'), 
            ]);
    }
}
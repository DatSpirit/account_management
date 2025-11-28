<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Services\AccountExpirationService;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Kiêm tra tài khoản hết hạn và đánh dấu
Artisan::command('accounts:check-expired', function () {
    $expirationService = app(AccountExpirationService::class);
    
    $this->info('Đang kiểm tra tài khoản hết hạn...');
    
    // Tìm các tài khoản đã hết hạn nhưng vẫn đang active
    $expiredUsers = User::where('account_status', 'active')
        ->whereNotNull('expires_at')
        ->where('expires_at', '<', Carbon::now())
        ->get();
    
    $count = $expiredUsers->count();
    
    if ($count === 0) {
        $this->info('✓ Không có tài khoản nào hết hạn.');
        return 0;
    }
    
    $this->warn("Tìm thấy {$count} tài khoản đã hết hạn.");
    
    $bar = $this->output->createProgressBar($count);
    $bar->start();
    
    foreach ($expiredUsers as $user) {
        $expirationService->markAsExpired($user);
        $bar->advance();
    }
    
    $bar->finish();
    $this->newLine(2);
    $this->info("✓ Đã đánh dấu {$count} tài khoản hết hạn.");
    
    return 0;
})->purpose('Kiểm tra và đánh dấu các tài khoản đã hết hạn');


// SCHEDULE - TỰ ĐỘNG CHẠY HÀNG NGÀY
Schedule::command('accounts:check-expired')->daily();
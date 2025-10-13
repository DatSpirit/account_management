<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Import các Fortify Contracts và Response Classes tùy chỉnh
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;
use App\Http\Responses\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse as RegisterViewResponseContract;
use App\Http\Responses\RegisterViewResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Liên kết (bind) các Contracts của Fortify với các Response Classes tùy chỉnh.
        // Đây là bước BẮT BUỘC để sửa lỗi "Target [Laravel\Fortify\Contracts\LoginViewResponse] is not instantiable."
        $this->app->bind(LoginViewResponseContract::class, LoginViewResponse::class);
        $this->app->bind(RegisterViewResponseContract::class, RegisterViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;
use Laravel\Fortify\Contracts\RegisterViewResponse as RegisterViewResponseContract;
use App\Http\Responses\LoginViewResponse;
use App\Http\Responses\RegisterViewResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event; // Import Event Facade
use Illuminate\Auth\Events\Login;      // Import Event Login
use App\Listeners\UpdateLastLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind các response tùy chỉnh cho Fortify
        $this->app->bind(LoginViewResponseContract::class, LoginViewResponse::class);
        $this->app->bind(RegisterViewResponseContract::class, RegisterViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Force HTTPS nếu ứng dụng chạy trên môi trường production
        //URL::forceScheme('https'); // comment this line if not using HTTPS

        // ✅ Load API routes
        $this->loadApiRoutes();

        /*
        |--------------------------------------------------------------------------
        | Định nghĩa View cho Fortify
        |--------------------------------------------------------------------------
        */
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        /*
        |--------------------------------------------------------------------------
        | Chuyển hướng sau khi đăng nhập/đăng ký
        |--------------------------------------------------------------------------
        |
        | Ở đây, ta ghi đè hành vi mặc định để sau khi người dùng đăng nhập
        | hoặc đăng ký thành công sẽ được chuyển hướng sang /dashboard
        |
        */
        app()->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        app()->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Responses\RegisterResponse::class
        );

        Event::listen(
            Login::class,
            UpdateLastLogin::class
        );
    }

    /**
     * ✅ Load API routes từ routes/api.php
     */
    protected function loadApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}

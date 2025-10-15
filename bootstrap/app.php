<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
  
   ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
        \App\Http\Middleware\VerifyCsrfToken::class,
    ]);

     // Đăng ký alias cho middleware tuỳ chỉnh
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);

    // Middleware mặc định của API
    $middleware->api([
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]);

   
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
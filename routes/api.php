<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OrderController;

// ✅ Webhook PayOS (không có middleware auth, không có CSRF)
Route::post('/payos/webhook', [WebhookController::class, 'handleWebhook'])
    ->name('api.payos.webhook');

// ✅ API tạo order
Route::post('/orders/create', [OrderController::class, 'createOrder'])
    ->name('api.orders.create');
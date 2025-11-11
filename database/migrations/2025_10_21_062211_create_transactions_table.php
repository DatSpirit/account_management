<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Liên kết đến người dùng và sản phẩm (có ràng buộc quan hệ)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->cascadeOnDelete();

            // Mã đơn hàng PayOS (số nguyên lớn)
            $table->unsignedBigInteger('order_code')->unique();

            // Số tiền thanh toán
            $table->decimal('amount', 15, 2)->default(0);

            // Trạng thái giao dịch
            $table->enum('status', ['pending', 'success', 'failed' , 'cancelled'])->default('pending');

            // Mô tả hoặc ghi chú thêm
            $table->string('description')->nullable();

            // Dữ liệu phản hồi từ PayOS (lưu raw JSON)
            $table->json('response_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

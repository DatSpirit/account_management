<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // ==========================================
            // BASIC INFORMATION
            // ==========================================
            
            // Liên kết đến người dùng và sản phẩm
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
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');

            // Mô tả hoặc ghi chú thêm
            $table->string('description')->nullable();

            // ==========================================
            // WEBHOOK SECURITY & DUPLICATE PREVENTION
            // ==========================================
            
            // Chặn trùng webhook
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            
            // Lưu thông tin webhook để verify
            $table->string('webhook_signature', 255)->nullable();
            $table->text('webhook_payload')->nullable();

            // ==========================================
            // PAYMENT DETAILS FROM PAYOS
            // ==========================================
            
            // Thông tin thanh toán chi tiết
            $table->string('payment_reference', 100)->nullable()->comment('Mã tham chiếu PayOS (FT...)');
            $table->string('payment_link_id', 100)->nullable()->comment('ID link thanh toán PayOS');
            
            // Thông tin tài khoản nhận tiền
            $table->string('account_number', 50)->nullable()->comment('Số TK nhận tiền');
            
            // Thông tin người chuyển tiền
            $table->string('counter_account_name', 255)->nullable()->comment('Tên người chuyển');
            $table->string('counter_account_number', 50)->nullable()->comment('Số TK người chuyển');
            $table->string('counter_account_bank_id', 20)->nullable()->comment('Mã ngân hàng người chuyển');
            $table->string('counter_account_bank_name', 100)->nullable()->comment('Tên ngân hàng người chuyển');
            
            // Thông tin giao dịch từ PayOS
            $table->timestamp('transaction_datetime')->nullable()->comment('Thời gian GD từ PayOS');
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');

            // ==========================================
            // LEGACY & BACKUP DATA
            // ==========================================
            
            // Dữ liệu phản hồi từ PayOS (lưu raw JSON - backup)
            $table->json('response_data')->nullable()->comment('Backup raw response từ PayOS');

            // ==========================================
            // TIMESTAMPS
            // ==========================================
            
            $table->timestamps();

            // ==========================================
            // INDEXES FOR PERFORMANCE
            // ==========================================
            
            // Index cho webhook processing
            $table->index(['is_processed', 'status'], 'idx_processed_status');
            $table->index('webhook_signature', 'idx_webhook_signature');
            
            // Index cho payment info
            $table->index('payment_reference', 'idx_payment_reference');
            $table->index('payment_link_id', 'idx_payment_link');
            
            // Index cho search & filter
            $table->index('created_at', 'idx_created_at');
            $table->index(['user_id', 'status'], 'idx_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
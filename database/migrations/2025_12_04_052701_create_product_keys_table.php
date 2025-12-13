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
        Schema::create('product_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');// Người sở hữu khóa sản phẩm
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');// Sản phẩm liên quan
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');// Giao dịch mua khóa sản phẩm

            // Thông tin khóa sản phẩm
            $table->string('key_code')->unique();// Mã khóa sản phẩm
            $table->enum('key_type', ['auto_generated', 'custom'])->default('auto_generated');// Loại khóa sản phẩm
            $table->integer('duration_minutes')->nullable();// Thời gian sử dụng (phút)
            $table->decimal('key_cost', 15, 2)->default(0);// Chi phí khóa sản phẩm

            // Trạng thái khóa sản phẩm và thời gian sử dụng
            $table->enum('status', ['active','expired','sunpended','revoked'])->default('active');// Trạng thái khóa sản phẩm
            $table->timestamp('activated_at')->nullable();// Thời gian kích hoạt khóa sản phẩm
            $table->timestamp('expires_at')->nullable();// Thời gian hết hạn khóa sản phẩm
            $table->timestamp('last_validated_at')->nullable();// Thời gian xác thực cuối cùng

            
            // Thông tin bổ sung
            $table->integer('validation_count')->default(0);// Số lần xác thực khóa sản phẩm
            $table->string('assigned_to_email')->nullable();// Email người được gán khóa sản phẩm
            $table->text('notes')->nullable();// Ghi chú bổ sung   
            $table->json('metadata')->nullable();// Dữ liệu bổ sung dưới dạng JSON

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','status']);
            $table->index(['key_code','status']);
            $table->index('expires_at');// Tạo chỉ mục để tối ưu truy vấn theo ngày hết hạn
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_keys');
    }
};

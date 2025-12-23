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
        Schema::create('key_validation_logs', function (Blueprint $table) {
            $table->id();// ID bản ghi
            $table->foreignId('product_key_id')->constrained()->onDelete('cascade');// Khóa sản phẩm được xác thực
            $table->string('key_code', 255);// Mã khóa sản phẩm

            // Thông tin xác thực
            $table->string('ip_address', 45)->nullable();// Địa chỉ IP từ đó thực hiện xác thực
            $table->string('user_agent')->nullable();// User agent của thiết bị xác thực
            $table->string('device_id')->nullable();// ID thiết bị (nếu có) có thể dùng để theo dõi
            $table->string('request_method', 10)->default('API');// Phương thức yêu cầu (API, Web, Mobile App, etc)

            // Kết quả xác thực
            $table->enum('validation_result', ['success', 'expired', 'invalid', 'suspended', 'suspicious',  'error']);// Kết quả xác thực
            $table->text('validation_message')->nullable();// Thông điệp chi tiết về kết quả xác thực
            $table->json('request_data')->nullable();// Dữ liệu yêu cầu xác thực dưới dạng JSON
            $table->json('response_data')->nullable();// Dữ liệu phản hồi xác thực dưới dạng JSON

            $table->timestamp('validated_at')->useCurrent();// Thời gian xác thực

            $table->index(['product_key_id', 'validated_at']);// Tối ưu truy vấn theo khóa sản phẩm và thời gian xác thực
            $table->index(['key_code', 'validation_result']);// Tối ưu truy vấn theo mã khóa và kết quả xác thực
            $table->index('ip_address');// Tối ưu truy vấn theo địa chỉ IP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_validation_logs');
    }
};

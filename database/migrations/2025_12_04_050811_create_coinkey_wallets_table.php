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
        Schema::create('coinkey_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0); // Số dư ví
            $table->decimal('total_deposited', 15, 2)->default(0); // Tổng số tiền đã nạp
            $table->decimal('total_spent', 15, 2)->default(0); // Tổng số tiền đã chi tiêu
            $table->string('currency', 10)->default('coinkey'); // Loại tiền tệ
            $table->boolean('is_locked')->default(false); // Trạng thái khóa ví nếu gặp sự cố
            $table->text('notes')->nullable(); // Ghi chú bổ sung
            $table->timestamps();

            $table->index('user_id');
        });


        // Tạo bảng lịch sử giao dịch ví Coinkey
        Schema::create('coinkey_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('coinkey_wallets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdraw', 'purchase', 'refund', 'admin_adjust']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('reference_type')->nullable(); // Loại tham chiếu giao dịch (ví dụ: Transaction, ProductKey, etc)
            $table->unsignedBigInteger('reference_id')->nullable(); // ID tham chiếu giao dịch
            $table->text('description')->nullable(); // Mô tả giao dịch
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung dưới dạng JSON
            $table->timestamps();

            $table->index('wallet_id', 'created_at');
            $table->index('user_id', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coinkey_transactions');
        Schema::dropIfExists('coinkey_wallets');
    }
};

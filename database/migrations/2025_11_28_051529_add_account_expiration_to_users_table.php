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
        Schema::table('users', function (Blueprint $table) {
            // Ngày hết hạn tài khoản (NULL = không giới hạn)
            $table->timestamp('expires_at')->nullable()->after('login_count');
            
            // Trạng thái tài khoản: active, expired, suspended
            $table->enum('account_status', ['active', 'expired', 'suspended'])
                  ->default('active')
                  ->after('expires_at');
            
            // Ghi chú về tài khoản (lý do suspend, thông tin gia hạn, v.v.)
            $table->text('account_notes')->nullable()->after('account_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'account_status', 'account_notes']);
        });
    }
};
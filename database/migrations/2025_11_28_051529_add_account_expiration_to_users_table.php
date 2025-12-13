<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thời hạn tài khoản
            $table->timestamp('expires_at')->nullable()->after('login_count');
            
            // Trạng thái tài khoản
            $table->enum('account_status', ['active', 'expired', 'suspended'])
                  ->default('active')
                  ->after('expires_at');
            
            // Ghi chú về tài khoản
            $table->text('account_notes')->nullable()->after('account_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'account_status', 'account_notes']);
        });
    }
};
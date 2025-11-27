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
           // Thêm cột để lưu trữ thời gian đăng nhập lần cuối. Có thể NULL
            $table->timestamp('last_login_at')->nullable()->after('password');
            
            // Thêm cột để đếm số lần đăng nhập. Mặc định là 0.
            $table->unsignedBigInteger('login_count')->default(0)->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           // Đảm bảo có thể rollback (hoàn tác) bằng cách xóa các cột đã thêm
            $table->dropColumn(['last_login_at', 'login_count']);
        });
    }
};

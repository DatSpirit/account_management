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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();// Khóa lưu trữ
            $table->mediumText('value');// Giá trị lưu trữ
            $table->integer('expiration');// Thời gian hết hạn 
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();// Khóa khóa
            $table->string('owner');// Chủ sở hữu khóa
            $table->integer('expiration');// Thời gian hết hạn khóa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};

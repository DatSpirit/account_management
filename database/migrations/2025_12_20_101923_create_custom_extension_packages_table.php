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
        Schema::create('custom_extension_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên gói
            $table->integer('days'); // Số ngày gia hạn
            $table->integer('duration_minutes'); // Quy đổi ra phút
            $table->decimal('price_vnd', 10, 2); // Giá VND
            $table->decimal('price_coinkey', 10, 2); // Giá Coinkey
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_extension_packages');
    }
};
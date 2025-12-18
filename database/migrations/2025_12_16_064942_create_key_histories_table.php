<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('key_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_key_id');
            $table->string('action'); // create, extend, suspend, activate, revoke
            $table->text('description')->nullable();
            $table->json('meta_data')->nullable(); // Lưu chi tiết cũ/mới
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_histories');
    }
};

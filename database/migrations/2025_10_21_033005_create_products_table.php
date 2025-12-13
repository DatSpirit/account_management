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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('General');

            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();

            // Loại sản phẩm: 'package' (Bán key) hoặc 'coinkey' (Nạp tiền)
            $table->enum('product_type', ['package', 'coinkey'])->default('package'); 

            // Nếu là 'package': Giá bán bằng Coinkey
            // Nếu là 'coinkey': Số lượng Coinkey nhận được
            $table->decimal('coinkey_amount', 15, 2)->nullable();
            
            // Thời hạn sử dụng (phút), chỉ dùng cho 'package'
            $table->integer('duration_minutes')->nullable()->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

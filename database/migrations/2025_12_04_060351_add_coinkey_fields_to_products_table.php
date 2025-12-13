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
        Schema::table('products', function (Blueprint $table) {

            //giá quy đổi 
            $table->decimal('price_per_minute', 10, 2)->after('product_type')->nullable(); // Giá quy đổi theo phút nếu sản phẩm là 'custom'
            $table->boolean('is_active')->after('price_per_minute')->default(true); // Trạng thái kích hoạt sản phẩm Coinkey
            $table->integer('stock')->after('is_active')->default(0); // Số lượng tồn kho sản phẩm Coinkey
            $table->integer('sold_count')->after('stock')->default(0); // Số lượng sản phẩm Coinkey đã bán

            $table->json('features')->after('sold_count')->nullable(); // Tính năng bổ sung của sản phẩm Coinkey dưới dạng JSON
            $table->integer('sort_order')->after('features')->default(0); // Thứ tự sắp xếp hiển thị sản phẩm Coinkey
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Xóa các trường liên quan đến Coinkey
            $table->dropColumn([
                'price_per_minute',
                'is_active',
                'stock',
                'sold_count',
                'features',
                'sort_order',
            ]);
        });
    }
};

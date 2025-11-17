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
        Schema::table('transactions', function (Blueprint $table) {
            //raw_payload: lưu chuỗi JSON thô (LONGTEXT)
            $table->longText('raw_payload')->nullable()->after('webhook_payload')->comment('Lưu JSON thô nhận từ webhook');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('raw_payload');
        });
    }
};

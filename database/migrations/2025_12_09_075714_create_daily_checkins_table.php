<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Streak tracking
            $table->integer('current_streak')->default(0); // Chuỗi điểm danh hiện tại
            $table->integer('longest_streak')->default(0); // Chuỗi dài nhất
            $table->integer('total_checkins')->default(0); // Tổng số lần điểm danh
            
            // Rewards tracking
            $table->decimal('total_earned', 15, 2)->default(0); // Tổng coinkey nhận được
            
            // Last check-in tracking
            $table->date('last_checkin_date')->nullable();
            $table->timestamp('last_checkin_at')->nullable();
            
            // Bonus tracking
            $table->json('milestone_rewards')->nullable(); // Lưu các mốc đã nhận thưởng
            
            $table->timestamps();

            // Indexes
            $table->unique('user_id');
            $table->index('last_checkin_date');
            $table->index('current_streak');
        });

        Schema::create('checkin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('checkin_date');
            $table->decimal('reward_amount', 15, 2);
            $table->integer('streak_day'); // Ngày thứ mấy trong chuỗi
            $table->boolean('is_bonus')->default(false); // Có phải ngày bonus không
            $table->string('bonus_type')->nullable(); // weekly, monthly, milestone
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'checkin_date']);
            $table->index('checkin_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkin_logs');
        Schema::dropIfExists('daily_checkins');
    }
};
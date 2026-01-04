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
        Schema::create('shifts', function (Blueprint $table) {
            // 勤務形態
            $table->string('work_type')->after('user_id');
            // normal / flex / short_time

            // 所定労働時間（分）
            $table->integer('daily_work_minutes')->after('work_type');

            // 標準休憩時間（分）
            $table->integer('break_minutes')->default(60)->change();

            // 通常・時短用の基準時間
            $table->time('standard_start_time')->nullable()->after('break_minutes');
            $table->time('standard_end_time')->nullable()->after('standard_start_time');

            // フレックス用コアタイム
            $table->time('core_start_time')->nullable()->after('standard_end_time');
            $table->time('core_end_time')->nullable()->after('core_start_time');

            // 適用期間
            $table->date('effective_from')->after('core_end_time');
            $table->date('effective_to')->nullable()->after('effective_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
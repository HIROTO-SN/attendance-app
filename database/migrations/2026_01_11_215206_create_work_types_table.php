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
        Schema::create('work_types', function (Blueprint $table) {
            $table->id()->comment('勤務形態ID');
            $table->string('code', 50)->unique()->comment('勤務形態コード（fixed, flex など）');
            $table->string('name', 100)->comment('勤務形態名（日本語）');
            $table->text('description')->nullable()->comment('勤務形態の説明・運用ルール');
            $table->timestamps();
        });

        // テーブルコメント（MySQL想定）
        DB::statement("ALTER TABLE work_types COMMENT = '勤務形態マスタ'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_types');
    }
};
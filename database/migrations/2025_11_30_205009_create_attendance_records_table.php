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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->timestamp('break_start')->nullable();
            $table->timestamp('break_end')->nullable();
            $table->integer('break_minutes')->default(0);
            $table->integer('working_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->enum('status', ['working','finished','absent','holiday','late','early_leave'])->default('working');
            $table->text('note')->nullable();
            $table->json('metadata')->nullable(); // GPS, IP など保存用
            $table->timestamps();

            $table->unique(['user_id','work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
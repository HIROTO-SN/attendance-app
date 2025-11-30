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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_code')->nullable()->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('position')->nullable();
            $table->enum('employment_type', ['fulltime','parttime','contract'])->default('fulltime');
            $table->date('join_date')->nullable();
            $table->date('leave_date')->nullable();
            $table->boolean('is_admin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
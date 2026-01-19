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
       Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('work_type_id')
                ->after('user_id')
                ->comment('勤務形態ID（work_types.id）');

            $table->foreign('work_type_id')->references('id')->on('work_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeign(['work_type_id']);
            $table->dropColumn('work_type_id');
        });
    }
};
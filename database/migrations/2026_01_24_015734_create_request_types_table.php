<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
    * Run the migrations.
    */

    public function up(): void {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique()
                ->comment('System identifier (e.g. punch_fix, overtime)');

            $table->string('name', 100)
                ->comment('Display name');

            $table->text('description')->nullable();

            $table->json('payload_schema')->nullable()
                ->comment('Defines request-specific input fields');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    */

    public function down(): void {
        Schema::dropIfExists( 'request_types' );
    }
}
;
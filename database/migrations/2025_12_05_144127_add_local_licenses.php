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
        Schema::create('local_licenses', function (Blueprint $table) {
            $table->id();
            $table->text('license_token');
            $table->timestamp('last_check_at')->nullable();
            $table->string('last_check_status')->nullable();
            $table->text('last_check_error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_licenses');
    }
};

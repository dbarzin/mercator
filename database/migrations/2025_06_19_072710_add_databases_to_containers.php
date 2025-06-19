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
        Schema::create('container_database', function (Blueprint $table) {
            $table->unsignedInteger('database_id');
            $table->unsignedInteger('container_id');

            $table->foreign('database_id')->references('id')->on('databases')->onDelete('cascade');
            $table->foreign('container_id')->references('id')->on('containers')->onDelete('cascade');

            $table->primary(['database_id', 'container_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_database');
    }
};

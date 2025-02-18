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
        // Create Application <-> Activity table
        Schema::create('activity_m_application', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('application_id_fk_0394834858');
            $table->unsignedInteger('activity_id')->index('process_id_fk_394823838');

            $table->foreign('m_application_id')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('activity_id')->references('id')->on('activities')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables
        Schema::dropIfExists('activity_m_application');
    }
};

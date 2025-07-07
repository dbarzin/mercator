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
        Schema::create('admin_user_m_application', function (Blueprint $table) {
            $table->unsignedInteger('admin_user_id');
            $table->unsignedInteger('m_application_id');

            // Clé primaire composée
            $table->primary(['admin_user_id', 'm_application_id']);

            // Clés étrangères
            $table->foreign('admin_user_id')
                ->references('id')
                ->on('admin_users')
                ->onDelete('cascade');

            $table->foreign('m_application_id')
                ->references('id')
                ->on('m_applications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_m_application');
    }
};

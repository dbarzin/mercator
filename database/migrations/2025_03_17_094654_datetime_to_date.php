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
        Schema::table('m_applications', function (Blueprint $table) {
            $table->date('install_date')->nullable()->change();
            $table->date('update_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_applications', function (Blueprint $table) {
            $table->datetime('install_date')->nullable()->change();
            $table->datetime('update_date')->nullable()->change();
        });
    }
};

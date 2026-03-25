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
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->boolean('active')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->boolean('active')->nullable(false)->change();
        });
    }
};

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
        //
        Schema::table('external_connected_entities', function (Blueprint $table) {
            $table->string('src_desc')->after('src')->nullable();
            $table->string('dest_desc')->after('dest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_connected_entities', function (Blueprint $table) {
            $table->dropColumn('src_desc');
            $table->dropColumn('dest_desc');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graphs', function (Blueprint $table) {
            $table->dropUnique('graphs_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('graphs', function (Blueprint $table) {
            $table->unique(['name', 'deleted_at'], 'graphs_name_unique');
        });
    }
};

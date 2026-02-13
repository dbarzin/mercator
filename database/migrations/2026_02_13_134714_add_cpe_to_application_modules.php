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
        Schema::table('application_modules', function (Blueprint $table) {
            $table->string('vendor')->after('description')->nullable();
            $table->string('product')->after('vendor')->nullable();
            $table->string('version')->after('product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_modules', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });
    }
};

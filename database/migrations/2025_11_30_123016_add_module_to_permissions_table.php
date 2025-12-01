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
        // Suppress soft deletes
        DB::table('permissions')->whereNotNull('deleted_at')->delete();

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module')->nullable()->after('title');
            $table->dropColumn('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('module');
            $table->timestamp('deleted_at')->nullable();
        });
    }
};

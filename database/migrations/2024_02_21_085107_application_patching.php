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
        Schema::table('m_applications', function(Blueprint $table) {
            $table->string('patching_group')->nullable();
            $table->integer('patching_frequency')->nullable();
            $table->date('next_update')->nullable();
            // $table->date('update_date')->change();
            // $table->date('install_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_applications', function(Blueprint $table) {
            $table->dropColumn('patching_group');
            $table->dropColumn('patching_frequency');
            $table->dropColumn('next_update');
            // $table->datetime('update_date')->change();
            // $table->datetime('install_date')->change();
        });
    }
};

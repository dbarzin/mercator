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
        Schema::table('vlans', function (Blueprint $table) {
            $table->integer('vlan_id')->after('description')->nullable();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('description')->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vlans', function (Blueprint $table) {
            $table->dropColumn('vlan_id');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->text('description')->change();
        });

    }
};

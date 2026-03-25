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
            $table->mediumText('icon')->nullable()->after('name');
        });
        Schema::table('workstations', function (Blueprint $table) {
            $table->mediumText('icon')->nullable()->after('name');
        });
        Schema::table('peripherals', function (Blueprint $table) {
            $table->mediumText('icon')->nullable()->after('name');
        });
        Schema::table('sites', function (Blueprint $table) {
            $table->mediumText('icon')->nullable()->after('name');
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->mediumText('icon')->nullable()->after('name');
        });
        Schema::table('admin_users', function (Blueprint $table) {
            $table->mediumText('icon')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('workstations', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};

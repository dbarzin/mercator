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
        Schema::table('networks', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('relations', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('macro_processuses', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('processes', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('m_applications', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });

        Schema::table('information', function (Blueprint $table) {
            $table->integer('security_need_auth')->after('security_need_t')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('relations', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('macro_processuses', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('processes', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });

        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('security_need_auth');
        });
    }
};

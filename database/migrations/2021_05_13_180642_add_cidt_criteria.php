<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCidtCriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('macro_processuses', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
        });
        Schema::table('macro_processuses', function (Blueprint $table) {
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });
        Schema::table('processes', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
        });
        Schema::table('processes', function (Blueprint $table) {
            // $table->renameColumn('security_need', 'security_need_c');
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });
        Schema::table('information', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
        });
        Schema::table('information', function (Blueprint $table) {
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });
        Schema::table('m_applications', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
        });
        Schema::table('m_applications', function (Blueprint $table) {
            // $table->renameColumn('security_need', 'security_need_c');
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });
        Schema::table('databases', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
        });
        Schema::table('databases', function (Blueprint $table) {
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('macro_processuses', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
        Schema::table('processes', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
        Schema::table('information', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
        Schema::table('m_applications', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
        Schema::table('databases', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
    }
}

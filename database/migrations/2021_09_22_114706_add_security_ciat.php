<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecurityCiat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->renameColumn('security_need', 'security_need_c');
            // $table->integer('security_need_i')->nullable();
            // $table->integer('security_need_a')->nullable();
            // $table->integer('security_need_t')->nullable();
        });
        Schema::table('networks', function (Blueprint $table) {
            // $table->renameColumn('security_need', 'security_need_c');
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
        Schema::table('networks', function (Blueprint $table) {
            $table->renameColumn('security_need_c', 'security_need');
            $table->dropColumn(['security_need_i', 'security_need_a', 'security_need_t']);
        });
    }
}
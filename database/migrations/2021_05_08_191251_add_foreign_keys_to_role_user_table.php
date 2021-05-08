<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign('role_id', 'role_id_fk_1470803')->references('id')->on('roles')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('user_id', 'user_id_fk_1470803')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign('role_id_fk_1470803');
            $table->dropForeign('user_id_fk_1470803');
        });
    }
}

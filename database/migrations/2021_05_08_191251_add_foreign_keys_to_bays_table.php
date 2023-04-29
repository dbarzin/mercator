<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bays', function (Blueprint $table) {
            $table->foreign('room_id', 'room_fk_1483441')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bays', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('room_fk_1483441');
            }
        });
    }
}

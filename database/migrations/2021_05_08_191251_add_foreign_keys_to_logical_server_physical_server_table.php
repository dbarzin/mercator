<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLogicalServerPhysicalServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logical_server_physical_server', function (Blueprint $table) {
            $table->foreign('logical_server_id', 'logical_server_id_fk_1657961')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('physical_server_id', 'physical_server_id_fk_1657961')->references('id')->on('physical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logical_server_physical_server', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('logical_server_id_fk_1657961');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_server_id_fk_1657961');
            }
        });
    }
}

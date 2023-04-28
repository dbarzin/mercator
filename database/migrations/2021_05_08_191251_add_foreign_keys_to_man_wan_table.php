<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToManWanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('man_wan', function (Blueprint $table) {
            $table->foreign('man_id', 'man_id_fk_1490367')->references('id')->on('mans')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('wan_id', 'wan_id_fk_1490367')->references('id')->on('wans')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('man_wan', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('man_id_fk_1490367');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('wan_id_fk_1490367');
            }
        });
    }
}

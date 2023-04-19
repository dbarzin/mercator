<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLanManTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lan_man', function (Blueprint $table) {
            $table->foreign('lan_id', 'lan_id_fk_1490345')->references('id')->on('lans')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('man_id', 'man_id_fk_1490345')->references('id')->on('mans')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lan_man', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('lan_id_fk_1490345');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('man_id_fk_1490345');
            }
        });
    }
}

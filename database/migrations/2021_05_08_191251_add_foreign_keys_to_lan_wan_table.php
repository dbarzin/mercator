<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLanWanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lan_wan', function (Blueprint $table) {
            $table->foreign('lan_id', 'lan_id_fk_1490368')->references('id')->on('lans')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('wan_id', 'wan_id_fk_1490368')->references('id')->on('wans')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lan_wan', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('lan_id_fk_1490368');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('wan_id_fk_1490368');
            }
        });
    }
}

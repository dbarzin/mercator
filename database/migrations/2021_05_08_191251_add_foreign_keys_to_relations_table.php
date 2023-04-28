<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relations', function (Blueprint $table) {
            $table->foreign('destination_id', 'destination_fk_1494373')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('source_id', 'source_fk_1494372')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relations', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('destination_fk_1494373');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('source_fk_1494372');
            }
        });
    }
}

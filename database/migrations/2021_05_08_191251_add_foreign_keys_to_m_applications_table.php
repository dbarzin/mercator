<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_applications', function (Blueprint $table) {
            $table->foreign('application_block_id', 'application_block_fk_1644592')->references('id')->on('application_blocks')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('entity_resp_id', 'entity_resp_fk_1488612')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_applications', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('application_block_fk_1644592');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('entity_resp_fk_1488612');
            }
        });
    }
}

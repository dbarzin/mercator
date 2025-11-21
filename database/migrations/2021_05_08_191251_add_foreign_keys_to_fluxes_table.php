<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFluxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fluxes', function (Blueprint $table) {
            $table->foreign('application_dest_id', 'application_dest_fk_1485549')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('application_source_id', 'application_source_fk_1485545')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('database_dest_id', 'database_dest_fk_1485552')->references('id')->on('databases')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('database_source_id', 'database_source_fk_1485548')->references('id')->on('databases')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('module_dest_id', 'module_dest_fk_1485551')->references('id')->on('application_modules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('module_source_id', 'module_source_fk_1485547')->references('id')->on('application_modules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('service_dest_id', 'service_dest_fk_1485550')->references('id')->on('application_services')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('service_source_id', 'service_source_fk_1485546')->references('id')->on('application_services')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fluxes', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('application_dest_fk_1485549');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('application_source_fk_1485545');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('database_dest_fk_1485552');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('database_source_fk_1485548');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('module_dest_fk_1485551');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('module_source_fk_1485547');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('service_dest_fk_1485550');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('service_source_fk_1485546');
            }
        });
    }
}

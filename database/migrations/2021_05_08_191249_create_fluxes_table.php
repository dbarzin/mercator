<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFluxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fluxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('application_source_id')->nullable()->index('application_source_fk_1485545');
            $table->unsignedInteger('service_source_id')->nullable()->index('service_source_fk_1485546');
            $table->unsignedInteger('module_source_id')->nullable()->index('module_source_fk_1485547');
            $table->unsignedInteger('database_source_id')->nullable()->index('database_source_fk_1485548');
            $table->unsignedInteger('application_dest_id')->nullable()->index('application_dest_fk_1485549');
            $table->unsignedInteger('service_dest_id')->nullable()->index('service_dest_fk_1485550');
            $table->unsignedInteger('module_dest_id')->nullable()->index('module_dest_fk_1485551');
            $table->unsignedInteger('database_dest_id')->nullable()->index('database_dest_fk_1485552');
            $table->boolean('crypted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fluxes');
    }
}

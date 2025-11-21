<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('descrition')->nullable();
            $table->string('responsible')->nullable();
            $table->longText('configuration')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('site_id')->nullable()->index('site_fk_1485322');
            $table->unsignedInteger('building_id')->nullable()->index('building_fk_1485323');
            $table->unsignedInteger('bay_id')->nullable()->index('bay_fk_1485324');
            $table->unsignedInteger('physical_switch_id')->nullable()->index('physical_switch_fk_8732342');
            $table->unique(['name', 'deleted_at'], 'physical_servers_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_servers');
    }
}

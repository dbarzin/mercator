<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalSwitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_switches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('site_id')->nullable()->index('site_fk_1485488');
            $table->unsignedInteger('building_id')->nullable()->index('building_fk_1485489');
            $table->unsignedInteger('bay_id')->nullable()->index('bay_fk_1485493');
            $table->unique(['name', 'deleted_at'], 'physical_switches_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_switches');
    }
}

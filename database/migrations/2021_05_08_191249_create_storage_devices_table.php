<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('site_id')->nullable()->index('site_fk_1485361');
            $table->unsignedInteger('building_id')->nullable()->index('building_fk_1485362');
            $table->unsignedInteger('bay_id')->nullable()->index('bay_fk_1485363');
            $table->unsignedInteger('physical_switch_id')->nullable()->index('physical_switch_fk_4025543');
            $table->unique(['name', 'deleted_at'], 'storage_devices_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_devices');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalSecurityDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_security_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('site_id')->nullable()->index('site_fk_1485517');
            $table->unsignedInteger('building_id')->nullable()->index('building_fk_1485518');
            $table->unsignedInteger('bay_id')->nullable()->index('bay_fk_1485519');
            $table->unique(['name', 'deleted_at'], 'physical_security_devices_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_security_devices');
    }
}

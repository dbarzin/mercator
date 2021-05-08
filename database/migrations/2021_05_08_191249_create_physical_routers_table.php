<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_routers', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('site_id')->nullable()->index('site_fk_1485497');
            $table->unsignedInteger('building_id')->nullable()->index('building_fk_1485498');
            $table->unsignedInteger('bay_id')->nullable()->index('bay_fk_1485499');
            $table->char('name')->nullable();
            $table->unique(['name', 'deleted_at'], 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_routers');
    }
}

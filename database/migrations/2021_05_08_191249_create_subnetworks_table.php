<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubnetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subnetworks', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description')->nullable();
            $table->string('address')->nullable();
            $table->string('ip_range')->nullable();
            $table->string('ip_allocation_type')->nullable();
            $table->string('responsible_exp')->nullable();
            $table->string('dmz')->nullable();
            $table->string('wifi')->nullable();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('connected_subnets_id')->nullable()->index('connected_subnets_fk_1483256');
            $table->unsignedInteger('gateway_id')->nullable()->index('gateway_fk_1492376');
            $table->unique(['name', 'deleted_at'], 'subnetwords_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subnetworks');
    }
}

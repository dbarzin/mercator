<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logical_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('net_services')->nullable();
            $table->longText('configuration')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('operating_system')->nullable();
            $table->string('address_ip')->nullable();
            $table->string('cpu')->nullable();
            $table->string('memory')->nullable();
            $table->string('environment')->nullable();
            $table->integer('disk')->nullable();
            $table->unique(['name', 'deleted_at'], 'logical_servers_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logical_servers');
    }
}

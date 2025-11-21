<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('protocol_type')->nullable();
            $table->string('responsible')->nullable();
            $table->string('responsible_sec')->nullable();
            $table->integer('security_need')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'networks_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('networks');
    }
}

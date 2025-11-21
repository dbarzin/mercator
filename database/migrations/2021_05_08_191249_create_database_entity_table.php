<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_entity', function (Blueprint $table) {
            $table->unsignedInteger('database_id')->index('database_id_fk_1485563');
            $table->unsignedInteger('entity_id')->index('entity_id_fk_1485563');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_entity');
    }
}

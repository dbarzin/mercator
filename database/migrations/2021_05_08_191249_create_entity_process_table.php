<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_process', function (Blueprint $table) {
            $table->unsignedInteger('process_id')->index('process_id_fk_1627958');
            $table->unsignedInteger('entity_id')->index('entity_id_fk_1627958');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_process');
    }
}

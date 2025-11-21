<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actor_operation', function (Blueprint $table) {
            $table->unsignedInteger('operation_id')->index('operation_id_fk_1472680');
            $table->unsignedInteger('actor_id')->index('actor_id_fk_1472680');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actor_operation');
    }
}

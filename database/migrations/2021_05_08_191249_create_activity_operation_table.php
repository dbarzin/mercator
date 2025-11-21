<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_operation', function (Blueprint $table) {
            $table->unsignedInteger('activity_id')->index('activity_id_fk_1472704');
            $table->unsignedInteger('operation_id')->index('operation_id_fk_1472704');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_operation');
    }
}

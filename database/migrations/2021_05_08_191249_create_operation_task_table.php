<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_task', function (Blueprint $table) {
            $table->unsignedInteger('operation_id')->index('operation_id_fk_1472749');
            $table->unsignedInteger('task_id')->index('task_id_fk_1472749');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_task');
    }
}

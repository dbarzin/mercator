<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_process', function (Blueprint $table) {
            $table->unsignedInteger('process_id')->index('process_id_fk_1627616');
            $table->unsignedInteger('activity_id')->index('activity_id_fk_1627616');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_process');
    }
}

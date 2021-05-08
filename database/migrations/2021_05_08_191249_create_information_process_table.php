<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_process', function (Blueprint $table) {
            $table->unsignedInteger('information_id')->index('information_id_fk_1473025');
            $table->unsignedInteger('process_id')->index('process_id_fk_1473025');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_process');
    }
}

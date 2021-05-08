<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_information', function (Blueprint $table) {
            $table->unsignedInteger('database_id')->index('database_id_fk_1485570');
            $table->unsignedInteger('information_id')->index('information_id_fk_1485570');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_information');
    }
}

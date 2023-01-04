<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseLogicalServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('database_logical_server', function (Blueprint $table) {
            $table->unsignedInteger('database_id')->index('database_id_fk_1542475');
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_1542475');
        });
   }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_logical_server');
    }
}

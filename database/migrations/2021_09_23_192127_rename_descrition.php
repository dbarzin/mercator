<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDescrition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('information', function(Blueprint $table) {
            $table->renameColumn('descrition', 'description');
        });        

        Schema::table('physical_servers', function(Blueprint $table) {
            $table->renameColumn('descrition', 'description');
        });        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('information', function(Blueprint $table) {
            $table->renameColumn('description', 'descrition');
        });        

        Schema::table('physical_servers', function(Blueprint $table) {
            $table->renameColumn('description', 'descrition');
        });        
    }
}

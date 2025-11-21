<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanManTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lan_man', function (Blueprint $table) {
            $table->unsignedInteger('man_id')->index('man_id_fk_1490345');
            $table->unsignedInteger('lan_id')->index('lan_id_fk_1490345');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lan_man');
    }
}

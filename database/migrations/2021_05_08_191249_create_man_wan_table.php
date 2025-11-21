<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManWanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_wan', function (Blueprint $table) {
            $table->unsignedInteger('wan_id')->index('wan_id_fk_1490367');
            $table->unsignedInteger('man_id')->index('man_id_fk_1490367');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('man_wan');
    }
}

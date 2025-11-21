<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanWanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lan_wan', function (Blueprint $table) {
            $table->unsignedInteger('wan_id')->index('wan_id_fk_1490368');
            $table->unsignedInteger('lan_id')->index('lan_id_fk_1490368');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lan_wan');
    }
}

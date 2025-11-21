<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomaineAdForestAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domaine_ad_forest_ad', function (Blueprint $table) {
            $table->unsignedInteger('forest_ad_id')->index('forest_ad_id_fk_1492084');
            $table->unsignedInteger('domaine_ad_id')->index('domaine_ad_id_fk_1492084');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domaine_ad_forest_ad');
    }
}

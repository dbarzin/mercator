<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifiant');
            $table->longText('description')->nullable();
            $table->string('owner')->nullable();
            $table->integer('security_need')->nullable();
            $table->longText('in_out')->nullable();
            $table->integer('dummy')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('macroprocess_id')->nullable()->index('process_fk_4342342');
            $table->unique(['identifiant', 'deleted_at'], 'processes_identifiant_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
    }
}

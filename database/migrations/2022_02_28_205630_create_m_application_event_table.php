<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMApplicationEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_application_events', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('user_id');
	        $table->unsignedInteger('m_application_id');
	        $table->longText('message');
	        $table->timestamps();
	        $table->foreign('user_id')->references('id')->on('users');
	        $table->foreign('m_application_id')->references('id')->on('m_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_application_events');
    }
}

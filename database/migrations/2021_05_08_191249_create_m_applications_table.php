<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_applications', function (Blueprint $table) {
	        $table->increments('id');
	        $table->string('name');
	        $table->longText('description')->nullable();
	        $table->integer('security_need')->nullable();
	        $table->string('responsible')->nullable();
	        $table->string('type')->nullable();
	        $table->string('technology')->nullable();
	        $table->string('external')->nullable();
	        $table->string('users')->nullable();
	        $table->timestamps();
	        $table->softDeletes();
	        $table->unsignedInteger('entity_resp_id')->nullable()->index('entity_resp_fk_1488612');
	        $table->unsignedInteger('application_block_id')->nullable()->index('application_block_fk_1644592');
	        $table->string('documentation')->nullable();
	        $table->unique(['name', 'deleted_at'], 'm_applications_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_applications');
    }
}

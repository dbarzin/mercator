<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('databases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('responsible')->nullable();
            $table->string('type')->nullable();
            $table->integer('security_need')->nullable();
            $table->string('external')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('entity_resp_id')->nullable()->index('entity_resp_fk_1485569');
            $table->unique(['name', 'deleted_at'], 'databases_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('databases');
    }
}

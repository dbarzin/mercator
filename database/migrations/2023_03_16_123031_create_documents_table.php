<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('mimetype');
            $table->integer('size');
            $table->string('hash');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('activity_document', function (Blueprint $table) {
            $table->unsignedInteger('activity_id')->index('activity_id_fk_1472714');
            $table->unsignedInteger('document_id')->index('operation_id_fk_1472714');
        });

        Schema::table('activity_document', function (Blueprint $table) {
            $table->foreign('activity_id', 'activity_id_fk_1472784')->references('id')->on('activities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('document_id', 'operation_id_fk_1472794')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::create('entity_document', function (Blueprint $table) {
            $table->unsignedInteger('entity_id')->index('activity_id_fk_4325433');
            $table->unsignedInteger('document_id')->index('operation_id_fk_4355431');
        });

        Schema::table('entity_document', function (Blueprint $table) {
            $table->foreign('entity_id', 'entity_id_fk_4325432')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('document_id', 'document_id_fk_4355430')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_document');
        Schema::dropIfExists('entity_document');
        Schema::dropIfExists('documents');
    }
}

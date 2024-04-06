<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add some fields to relations
        Schema::table('relations', function(Blueprint $table) {
            $table->string('attributes')->nullable();
            $table->string('reference')->nullable();
            $table->string('responsible')->nullable();
            $table->string('order_number')->nullable();
            $table->boolean('active')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('comments')->nullable();
            $table->integer('security_need_c')->nullable();
            $table->integer('security_need_i')->nullable();
            $table->integer('security_need_a')->nullable();
            $table->integer('security_need_t')->nullable();
        });

        // Values of a relation
        Schema::create('relation_values', function (Blueprint $table) {
            $table->unsignedInteger('relation_id')->index('relation_id_fk_43243244');
            $table->foreign('relation_id', 'relation_id_fk_43243244')->references('id')->on('relations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->date('date_price')->nullable();
            $table->decimal('price',12,2)->nullable();
            // add timestamp
            $table->timestamps();
        });

        // Link between documents and relations
        Schema::create('document_relation', function (Blueprint $table) {
            $table->unsignedInteger('relation_id')->index('relation_id_fk_6948334');
            $table->unsignedInteger('document_id')->index('document_id_fk_5492844');
            $table->foreign('relation_id', 'relation_id_fk_6948334')->references('id')->on('relations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('document_id', 'document_id_fk_5492844')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // Add some fields to entities
        Schema::table('entities', function(Blueprint $table) {
            $table->string('attributes')->nullable();
            $table->string('reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove some fields from relations
        Schema::table('relations', function(Blueprint $table) {
            $table->dropColumn('attributes');
            $table->dropColumn('reference');
            $table->dropColumn('responsible');
            $table->dropColumn('order_number');
            $table->dropColumn('active');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('comments');
            $table->dropColumn('security_need_c');
            $table->dropColumn('security_need_i');
            $table->dropColumn('security_need_a');
            $table->dropColumn('security_need_t');
        });

        // Values of a relation
        Schema::table('relation_values', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('relation_id_fk_43243244');
            }
        });
        Schema::dropIfExists('relation_values');

        // Link between documents and applications
        Schema::table('document_relation', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('relation_id_fk_6948334');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_5492844');
            }
        });
        Schema::dropIfExists('document_relation');

        // Remove some fields from entities
        Schema::table('entities', function(Blueprint $table) {
            $table->dropColumn('attributes');
            $table->dropColumn('reference');
        });
    }
};

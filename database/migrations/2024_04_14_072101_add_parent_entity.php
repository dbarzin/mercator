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
        Schema::table('entities', function(Blueprint $table) {
            $table->unsignedInteger('parent_entity_id')->index('entity_id_fk_4398013')->nullable();
            $table->foreign('parent_entity_id','entity_id_fk_4398013')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('entity_id_fk_4398013');
            }
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('parent_entity_id');
        });
    }

};

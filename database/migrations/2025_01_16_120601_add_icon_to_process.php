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
        Schema::table('processes', function (Blueprint $table) {
            $table->unsignedInteger('icon_id')->after("name")->nullable()->index('document_id_fk_5938654');
            $table->foreign('icon_id','document_id_fk_5938654')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop icon_id on Process
        Schema::table('processes', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_5938654');
            }
        });

        Schema::table('processes', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

    }
};

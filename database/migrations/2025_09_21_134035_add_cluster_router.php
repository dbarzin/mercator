<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_4398834')->nullable();
            $table->foreign('cluster_id', 'cluster_id_fk_4398834')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('cluster_id_fk_4398834');
            }
        });

        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });
    }
};

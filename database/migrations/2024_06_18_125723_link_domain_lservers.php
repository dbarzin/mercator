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
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->unsignedInteger('domain_id')->index('domain_id_fk_493844')->nullable();
            $table->foreign('domain_id', 'domain_id_fk_493844')->references('id')->on('domaine_ads')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('domain_id_fk_493844');
            }
        });

        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropColumn('domain_id');
        });
    }
};

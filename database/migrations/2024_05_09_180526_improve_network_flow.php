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
        Schema::table('logical_flows', function (Blueprint $table) {
            $table->unsignedInteger('router_id')->index('router_id_fk_4382393')->nullable();
            $table->foreign('router_id', 'router_id_fk_4382393')->references('id')->on('routers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->integer('priority')->nullable();
            $table->string('action')->nullable();
            $table->string('users')->nullable();
            $table->string('interface')->nullable();
            $table->string('schedule')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('logical_flows', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('router_id_fk_4382393');
            }
        });

        Schema::table('logical_flows', function (Blueprint $table) {
            $table->dropColumn('router_id');
            $table->dropColumn('priority');
            $table->dropColumn('action');
            $table->dropColumn('users');
            $table->dropColumn('interface');
            $table->dropColumn('schedule');
        });
    }
};

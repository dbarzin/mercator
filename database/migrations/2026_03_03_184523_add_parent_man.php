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
        Schema::table('mans', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('name');
            $table->unsignedInteger('parent_man_id')->index('man_id_fk_4385454')->nullable()->after('description');
            $table->foreign('parent_man_id', 'man_id_fk_4385454')->references('id')->on('mans')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mans', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('man_id_fk_4385454');
            }
        });

        Schema::table('mans', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('parent_man_id');
        });
    }
};

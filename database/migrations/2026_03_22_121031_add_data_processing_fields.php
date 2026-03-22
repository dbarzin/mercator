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
        Schema::table('data_processing', function (Blueprint $table) {
            $table->text('data_source')->after('categories')->nullable();
            $table->text('data_collection_obligation')->after('data_source')->nullable();
            $table->text('data_subject_rights')->after('retention')->nullable();
            $table->text('automated_decision_making')->after('transfert')->nullable();
            $table->date('update_date')->after('data_subject_rights')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_processing', function (Blueprint $table) {
            $table->dropColumn('data_source');
            $table->dropColumn('data_collection_obligation');
            $table->dropColumn('data_subject_rights');
            $table->dropColumn('automated_decision_making');
        });
    }
};

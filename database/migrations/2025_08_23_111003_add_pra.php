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
        Schema::table('activities', function (Blueprint $table) {
            $table->text('drp')->nullable()->after('description');
            $table->string('drp_link')->nullable()->after('drp');

            // Drop old columns
            $table->dropColumn(['recovery_time_objective', 'maximum_tolerable_downtime']);
            $table->dropColumn(['recovery_point_objective', 'maximum_tolerable_data_loss']);

            // RTO / MTD : maximum acceptable downtime for an activity
            $table->integer('recovery_time_objective')->nullable()->after('description');
            $table->integer('maximum_tolerable_downtime')->nullable()->after('recovery_time_objective');

            // RPO / MTDL : maximum acceptable data loss in case of incident
            $table->integer('recovery_point_objective')->nullable()->after('maximum_tolerable_downtime');
            $table->integer('maximum_tolerable_data_loss')->nullable()->after('recovery_point_objective');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('drp');
            $table->dropColumn('drp_link');

            // Drop old columns
            $table->dropColumn(['recovery_time_objective', 'maximum_tolerable_downtime']);
            $table->dropColumn(['recovery_point_objective', 'maximum_tolerable_data_loss']);

            // RTO / MTD : maximum acceptable downtime for an activity
            $table->time('recovery_time_objective')->nullable()->after('description');
            $table->time('maximum_tolerable_downtime')->nullable()->after('recovery_time_objective');

            // RPO / MTDL : maximum acceptable data loss in case of incident
            $table->time('recovery_point_objective')->nullable()->after('maximum_tolerable_downtime');
            $table->time('maximum_tolerable_data_loss')->nullable()->after('recovery_point_objective');
        });
    }
};

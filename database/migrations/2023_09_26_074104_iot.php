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
        Schema::table('peripherals', function (Blueprint $table) {
            $table->string('domain')->nullable();
            // add link to entities
            $table->unsignedInteger('provider_id')->nullable()->index('entity_id_fk_4383234');
            $table->foreign('provider_id', 'entity_id_fk_4383234')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        // Peripherals <-> applications
        Schema::create('m_application_peripheral', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_9878654');
            $table->unsignedInteger('peripheral_id')->index('peripheral_id_fk_6454564');
            $table->foreign('m_application_id', 'm_application_id_fk_9878654')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('peripheral_id', 'peripheral_id_fk_6454564')->references('id')->on('peripherals')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn(['domain']);
            // remove link to entities
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('entity_id_fk_4383234');
            }
            $table->dropColumn(['provider_id']);
        });

        Schema::dropIfExists('m_application_peripheral');
    }
};

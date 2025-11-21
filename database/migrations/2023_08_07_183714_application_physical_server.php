<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_application_physical_server', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_5483543');
            $table->unsignedInteger('physical_server_id')->index('physical_server_id_fk_4543543');
            $table->foreign('physical_server_id', 'physical_server_id_fk_4543543')->references('id')->on('physical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_5483543')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_application_physical_server', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_server_id_fk_4543543');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('m_application_id_fk_5483543');
            }
        });

        Schema::dropIfExists('m_application_physical_server');

    }
};

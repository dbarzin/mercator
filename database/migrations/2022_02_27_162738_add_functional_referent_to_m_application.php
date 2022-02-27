<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFunctionalReferentToMApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_applications', function (Blueprint $table) {
            $table->string('functional_referent')->nullable()->after('responsible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn('functional_referent');
        });
    }
}

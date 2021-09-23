<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

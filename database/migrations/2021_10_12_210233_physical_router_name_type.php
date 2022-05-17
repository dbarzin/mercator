<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhysicalRouterNameType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_routers', function (Blueprint $table) {
            $table->string('name')->change();
        });
        // For PostgresSQL : ALTER TABLE physical_routers ALTER COLUMN name TYPE varchar(255);
        // DB::statement("ALTER TABLE physical_routers CHANGE name name varchar(255)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_routers', function (Blueprint $table) {
             $table->char('name')->change();
        });    
        // DB::statement("ALTER TABLE physical_routers CHANGE name name char(255)");
    }
}

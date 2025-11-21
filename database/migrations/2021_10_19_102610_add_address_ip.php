<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressIp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dnsservers', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('dhcp_servers', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dnsservers', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });
        Schema::table('dhcp_servers', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });
    }
}

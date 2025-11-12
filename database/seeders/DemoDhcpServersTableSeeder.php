<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDhcpServersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('dhcp_servers')->delete();

    }
}

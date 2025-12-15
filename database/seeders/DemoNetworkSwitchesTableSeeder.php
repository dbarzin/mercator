<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoNetworkSwitchesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('network_switches')->delete();

    }
}

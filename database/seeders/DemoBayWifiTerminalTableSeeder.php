<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoBayWifiTerminalTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('bay_wifi_terminal')->delete();

    }
}

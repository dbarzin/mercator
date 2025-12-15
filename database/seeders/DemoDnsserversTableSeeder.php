<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDnsserversTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('dnsservers')->delete();

    }
}

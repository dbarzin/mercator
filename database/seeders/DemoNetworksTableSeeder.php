<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoNetworksTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('networks')->delete();

    }
}

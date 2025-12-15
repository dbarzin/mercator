<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLanWanTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('lan_wan')->delete();

    }
}

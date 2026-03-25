<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMansTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('mans')->delete();

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLansTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('lans')->delete();

    }
}

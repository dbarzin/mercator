<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRoutersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('routers')->delete();

    }
}

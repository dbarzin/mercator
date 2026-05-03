<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLogicalServerApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_logical_server')->delete();

    }
}

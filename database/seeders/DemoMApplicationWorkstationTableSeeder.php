<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationWorkstationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_workstation')->delete();

    }
}

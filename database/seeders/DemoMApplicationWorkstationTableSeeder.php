<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMApplicationWorkstationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('m_application_workstation')->delete();

    }
}

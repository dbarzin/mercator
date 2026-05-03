<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMApplicationEventsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_events')->delete();

    }
}

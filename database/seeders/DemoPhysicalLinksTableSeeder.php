<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhysicalLinksTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('physical_links')->delete();

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMediaTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('media')->delete();

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoGatewaysTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('gateways')->delete();

    }
}

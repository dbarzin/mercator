<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoCpeProductsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('cpe_products')->delete();

    }
}

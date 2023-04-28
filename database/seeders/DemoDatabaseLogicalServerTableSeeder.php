<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseLogicalServerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('database_logical_server')->delete();
        
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLogicalServerPhysicalServerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('logical_server_physical_server')->delete();
        
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoExternalConnectedEntitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('external_connected_entities')->delete();
        
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhysicalRoutersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('physical_routers')->delete();
        
        \DB::table('physical_routers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => '<p>Routeur prncipal</p>',
                'type' => 'Fortinet',
                'created_at' => '2020-07-10 08:58:53',
                'updated_at' => '2021-10-12 21:08:21',
                'deleted_at' => NULL,
                'site_id' => 1,
                'building_id' => 1,
                'bay_id' => 1,
                'name' => 'R1',
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => '<p>Routeur secondaire</p>',
                'type' => 'CISCO',
                'created_at' => '2020-07-10 09:19:11',
                'updated_at' => '2020-07-25 10:28:17',
                'deleted_at' => NULL,
                'site_id' => 2,
                'building_id' => 3,
                'bay_id' => 5,
                'name' => 'R2',
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoWifiTerminalsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wifi_terminals')->delete();
        
        \DB::table('wifi_terminals')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'WIFI_01',
                'description' => '<p>Borne wifi 01</p>',
                'type' => 'Alcatel 3500',
                'created_at' => '2020-07-22 16:44:37',
                'updated_at' => '2020-07-22 16:44:37',
                'deleted_at' => NULL,
                'site_id' => 1,
                'building_id' => 2,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'WIFI_02',
                'description' => '<p>Borne Wifi 2</p>',
                'type' => 'ALCALSYS 3001',
                'created_at' => '2021-06-07 16:37:47',
                'updated_at' => '2021-06-07 16:37:47',
                'deleted_at' => NULL,
                'site_id' => 2,
                'building_id' => 1,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'WIFI_03',
                'description' => '<p>Borne Wifi 3</p>',
                'type' => 'SYSTEL 3310',
                'created_at' => '2021-06-07 16:42:29',
                'updated_at' => '2021-06-07 16:43:18',
                'deleted_at' => NULL,
                'site_id' => 3,
                'building_id' => 4,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoStorageDevicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('storage_devices')->delete();
        
        \DB::table('storage_devices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'DiskServer 1',
                'description' => '<p>Description du serveur d stockage 1</p>',
                'created_at' => '2020-06-21 17:30:16',
                'updated_at' => '2020-06-21 17:30:16',
                'deleted_at' => NULL,
                'site_id' => 1,
                'building_id' => 2,
                'bay_id' => 3,
                'physical_switch_id' => NULL,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Oracle Server',
                'description' => '<p>Main oracle server</p>',
                'created_at' => '2020-06-21 17:33:51',
                'updated_at' => '2020-06-21 17:34:38',
                'deleted_at' => NULL,
                'site_id' => 1,
                'building_id' => 2,
                'bay_id' => 2,
                'physical_switch_id' => NULL,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
        ));
        
        
    }
}
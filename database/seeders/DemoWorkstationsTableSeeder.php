<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoWorkstationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('workstations')->delete();
        
        \DB::table('workstations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Workstation 1',
                'description' => '<p>Station de travail compta</p>',
                'created_at' => '2020-06-21 17:09:04',
                'updated_at' => '2022-03-20 12:37:13',
                'deleted_at' => NULL,
                'site_id' => 1,
                'building_id' => 7,
                'physical_switch_id' => NULL,
                'type' => 'ThinThink 460',
                'operating_system' => NULL,
                'address_ip' => NULL,
                'cpu' => NULL,
                'memory' => NULL,
                'disk' => NULL,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Workstation 2',
                'description' => '<p>Station de travail accueil</p>',
                'created_at' => '2020-06-21 17:09:54',
                'updated_at' => '2021-10-20 09:14:59',
                'deleted_at' => NULL,
                'site_id' => 2,
                'building_id' => 3,
                'physical_switch_id' => NULL,
                'type' => 'ThinThink 410',
                'operating_system' => NULL,
                'address_ip' => NULL,
                'cpu' => NULL,
                'memory' => NULL,
                'disk' => NULL,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Workstation 3',
                'description' => '<p>Station de travail back-office</p>',
                'created_at' => '2020-06-21 17:17:57',
                'updated_at' => '2021-10-20 09:15:25',
                'deleted_at' => NULL,
                'site_id' => 2,
                'building_id' => 4,
                'physical_switch_id' => NULL,
                'type' => 'ThinThink 420',
                'operating_system' => NULL,
                'address_ip' => NULL,
                'cpu' => NULL,
                'memory' => NULL,
                'disk' => NULL,
                'vendor' => NULL,
                'product' => NULL,
                'version' => NULL,
            ),
        ));
        
        
    }
}
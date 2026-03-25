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

        \DB::table('workstations')->insert([
            0 => [
                'id' => 1,
                'name' => 'Workstation 1',
                'description' => '<p>Station de travail compta</p>',
                'created_at' => '2020-06-21 17:09:04',
                'updated_at' => '2022-03-20 12:37:13',
                'deleted_at' => null,
                'site_id' => 1,
                'building_id' => 7,
                'physical_switch_id' => null,
                'type' => 'ThinThink 460',
                'operating_system' => null,
                'address_ip' => null,
                'cpu' => null,
                'memory' => null,
                'disk' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Workstation 2',
                'description' => '<p>Station de travail accueil</p>',
                'created_at' => '2020-06-21 17:09:54',
                'updated_at' => '2021-10-20 09:14:59',
                'deleted_at' => null,
                'site_id' => 2,
                'building_id' => 3,
                'physical_switch_id' => null,
                'type' => 'ThinThink 410',
                'operating_system' => null,
                'address_ip' => null,
                'cpu' => null,
                'memory' => null,
                'disk' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'Workstation 3',
                'description' => '<p>Station de travail back-office</p>',
                'created_at' => '2020-06-21 17:17:57',
                'updated_at' => '2021-10-20 09:15:25',
                'deleted_at' => null,
                'site_id' => 2,
                'building_id' => 4,
                'physical_switch_id' => null,
                'type' => 'ThinThink 420',
                'operating_system' => null,
                'address_ip' => null,
                'cpu' => null,
                'memory' => null,
                'disk' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoVlansTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('vlans')->delete();

        \DB::table('vlans')->insert([
            0 => [
                'id' => 1,
                'name' => 'VLAN_2',
                'description' => 'VLAN Wifi',
                'created_at' => '2020-07-07 16:31:53',
                'updated_at' => '2020-07-07 16:39:10',
                'deleted_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'VLAN_1',
                'description' => 'VLAN publc',
                'created_at' => '2020-07-07 16:34:30',
                'updated_at' => '2020-07-07 16:38:53',
                'deleted_at' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'VLAN_3',
                'description' => 'VLAN application',
                'created_at' => '2020-07-07 16:38:41',
                'updated_at' => '2020-07-08 21:35:53',
                'deleted_at' => null,
            ],
            3 => [
                'id' => 4,
                'name' => 'VLAN_4',
                'description' => 'Vlan Client',
                'created_at' => '2020-07-08 21:34:11',
                'updated_at' => '2020-07-08 21:36:06',
                'deleted_at' => null,
            ],
            4 => [
                'id' => 5,
                'name' => 'VLAN_5',
                'description' => 'Test Production',
                'created_at' => '2020-07-11 19:12:03',
                'updated_at' => '2021-08-18 19:35:54',
                'deleted_at' => null,
            ],
            5 => [
                'id' => 6,
                'name' => 'VLAN_6',
                'description' => 'VLAN démilitarisé',
                'created_at' => '2020-07-11 19:14:55',
                'updated_at' => '2021-08-18 19:36:12',
                'deleted_at' => null,
            ],
            6 => [
                'id' => 7,
                'name' => 'VLAN_7',
                'description' => 'Description du VLAN 7',
                'created_at' => '2021-09-07 18:35:28',
                'updated_at' => '2021-09-07 18:35:28',
                'deleted_at' => null,
            ],
            7 => [
                'id' => 8,
                'name' => 'VLAN_8',
                'description' => 'Description du VLAN 8',
                'created_at' => '2021-09-07 18:36:20',
                'updated_at' => '2021-09-07 18:36:20',
                'deleted_at' => null,
            ],
        ]);

    }
}

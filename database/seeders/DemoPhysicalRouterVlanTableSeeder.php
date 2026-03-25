<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhysicalRouterVlanTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('physical_router_vlan')->delete();

        \DB::table('physical_router_vlan')->insert([
            0 => [
                'physical_router_id' => 1,
                'vlan_id' => 1,
            ],
            1 => [
                'physical_router_id' => 1,
                'vlan_id' => 3,
            ],
            2 => [
                'physical_router_id' => 2,
                'vlan_id' => 3,
            ],
        ]);

    }
}

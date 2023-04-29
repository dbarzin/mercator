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
        
        \DB::table('physical_router_vlan')->insert(array (
            0 => 
            array (
                'physical_router_id' => 1,
                'vlan_id' => 1,
            ),
            1 => 
            array (
                'physical_router_id' => 1,
                'vlan_id' => 3,
            ),
            2 => 
            array (
                'physical_router_id' => 2,
                'vlan_id' => 3,
            ),
        ));
        
        
    }
}
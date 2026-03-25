<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhysicalSecurityDevicesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('physical_security_devices')->delete();

        \DB::table('physical_security_devices')->insert([
            0 => [
                'id' => 1,
                'name' => 'Magic Gate',
                'type' => 'Gate',
                'description' => '<p>BIG Magic Gate</p>',
                'created_at' => '2021-05-20 16:40:43',
                'updated_at' => '2021-11-13 21:29:45',
                'deleted_at' => null,
                'site_id' => 1,
                'building_id' => 1,
                'bay_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Magic Firewall',
                'type' => 'Firewall',
                'description' => '<p>The magic firewall - PT3743</p>',
                'created_at' => '2021-06-07 16:56:26',
                'updated_at' => '2021-11-13 21:29:32',
                'deleted_at' => null,
                'site_id' => 2,
                'building_id' => 3,
                'bay_id' => 5,
            ],
            2 => [
                'id' => 3,
                'name' => 'Sensor-1',
                'type' => 'Sensor',
                'description' => '<p>Temperature sensor</p>',
                'created_at' => '2021-11-13 21:37:14',
                'updated_at' => '2021-11-13 21:37:56',
                'deleted_at' => null,
                'site_id' => 1,
                'building_id' => 3,
                'bay_id' => null,
            ],
        ]);

    }
}

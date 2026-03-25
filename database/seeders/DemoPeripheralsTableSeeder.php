<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPeripheralsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('peripherals')->delete();

        \DB::table('peripherals')->insert([
            0 => [
                'id' => 1,
                'name' => 'PER_01',
                'type' => 'IBM 3400',
                'description' => '<p>important peripheral</p>',
                'responsible' => 'Marcel',
                'created_at' => '2020-07-25 08:18:40',
                'updated_at' => '2020-07-25 08:19:46',
                'deleted_at' => null,
                'site_id' => 1,
                'building_id' => 2,
                'bay_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'PER_02',
                'type' => 'IBM 5600',
                'description' => '<p>Description</p>',
                'responsible' => 'Nestor',
                'created_at' => '2020-07-25 08:19:18',
                'updated_at' => '2020-07-25 08:19:18',
                'deleted_at' => null,
                'site_id' => 3,
                'building_id' => 5,
                'bay_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'PER_03',
                'type' => 'HAL 8100',
                'description' => '<p>Space device</p>',
                'responsible' => 'Niel',
                'created_at' => '2020-07-25 08:19:58',
                'updated_at' => '2020-07-25 08:20:18',
                'deleted_at' => null,
                'site_id' => 3,
                'building_id' => 4,
                'bay_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
        ]);

    }
}

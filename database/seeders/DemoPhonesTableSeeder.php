<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhonesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('phones')->delete();

        \DB::table('phones')->insert([
            0 => [
                'id' => 1,
                'name' => 'Phone 01',
                'description' => '<p>Téléphone de test</p>',
                'type' => 'MOTOROAL 3110',
                'created_at' => '2020-07-21 07:16:46',
                'updated_at' => '2020-07-25 09:15:17',
                'deleted_at' => null,
                'site_id' => 1,
                'building_id' => 1,
                'physical_switch_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Phone 03',
                'description' => '<p>Special AA phone</p>',
                'type' => 'Top secret red phne',
                'created_at' => '2020-07-21 07:18:01',
                'updated_at' => '2020-07-25 09:25:38',
                'deleted_at' => null,
                'site_id' => 2,
                'building_id' => 4,
                'physical_switch_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'Phone 02',
                'description' => '<p>Description phone 02</p>',
                'type' => 'IPhone 2',
                'created_at' => '2020-07-25 08:52:23',
                'updated_at' => '2020-07-25 09:25:19',
                'deleted_at' => null,
                'site_id' => 2,
                'building_id' => 3,
                'physical_switch_id' => null,
                'vendor' => null,
                'product' => null,
                'version' => null,
            ],
        ]);

    }
}

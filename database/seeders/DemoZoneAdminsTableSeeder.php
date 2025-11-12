<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoZoneAdminsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('zone_admins')->delete();

        \DB::table('zone_admins')->insert([
            0 => [
                'id' => 1,
                'name' => 'Enreprise',
                'description' => '<p>Zone d\'administration de l\'entreprise</p>',
                'created_at' => '2020-07-03 09:49:03',
                'updated_at' => '2021-05-23 15:07:18',
                'deleted_at' => null,
            ],
        ]);

    }
}

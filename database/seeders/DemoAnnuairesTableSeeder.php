<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAnnuairesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('annuaires')->delete();

        \DB::table('annuaires')->insert([
            0 => [
                'id' => 1,
                'name' => 'AD01',
                'description' => '<p>Annuaire principal&nbsp;</p>',
                'solution' => 'Acive Directory',
                'created_at' => '2020-07-03 09:49:37',
                'updated_at' => '2022-03-22 20:33:39',
                'deleted_at' => null,
                'zone_admin_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Mercator',
                'description' => '<p>Cartographie du système d\'information</p>',
                'solution' => 'Logiciel développé maison',
                'created_at' => '2020-07-03 12:24:48',
                'updated_at' => '2020-07-13 17:12:59',
                'deleted_at' => null,
                'zone_admin_id' => 1,
            ],
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationBlocksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('application_blocks')->delete();

        \DB::table('application_blocks')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Bloc applicatif 1',
                'description' => '<p>Description du bloc applicatif</p>',
                'responsible' => 'Jean Pierre',
                'created_at' => '2020-06-13 06:09:01',
                'updated_at' => '2020-06-13 06:09:01',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Bloc applicatif 2',
                'description' => '<p>Second bloc applicatif.</p>',
                'responsible' => 'Marcel pierre',
                'created_at' => '2020-06-13 06:10:52',
                'updated_at' => '2020-06-17 18:13:33',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Bloc applicatif 3',
                'description' => '<p>Description du block applicatif 3</p>',
                'responsible' => 'Nestor',
                'created_at' => '2020-08-29 14:00:10',
                'updated_at' => '2022-03-20 18:53:29',
                'deleted_at' => NULL,
            ),
        ));


    }
}

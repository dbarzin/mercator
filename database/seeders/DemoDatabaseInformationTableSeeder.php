<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseInformationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('database_information')->delete();

        \DB::table('database_information')->insert([
            0 => [
                'database_id' => 1,
                'information_id' => 1,
            ],
            1 => [
                'database_id' => 1,
                'information_id' => 2,
            ],
            2 => [
                'database_id' => 1,
                'information_id' => 3,
            ],
            3 => [
                'database_id' => 3,
                'information_id' => 2,
            ],
            4 => [
                'database_id' => 3,
                'information_id' => 3,
            ],
            5 => [
                'database_id' => 5,
                'information_id' => 1,
            ],
            6 => [
                'database_id' => 4,
                'information_id' => 2,
            ],
            7 => [
                'database_id' => 6,
                'information_id' => 2,
            ],
            8 => [
                'database_id' => 6,
                'information_id' => 3,
            ],
            9 => [
                'database_id' => 5,
                'information_id' => 5,
            ],
        ]);

    }
}

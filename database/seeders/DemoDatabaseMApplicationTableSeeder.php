<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_database')->delete();

        \DB::table('application_database')->insert([
            0 => [
                'application_id' => 2,
                'database_id' => 3,
            ],
            1 => [
                'application_id' => 3,
                'database_id' => 4,
            ],
            2 => [
                'application_id' => 3,
                'database_id' => 1,
            ],
            3 => [
                'application_id' => 4,
                'database_id' => 5,
            ],
            4 => [
                'application_id' => 4,
                'database_id' => 6,
            ],
            5 => [
                'application_id' => 15,
                'database_id' => 5,
            ],
            6 => [
                'application_id' => 15,
                'database_id' => 4,
            ],
            7 => [
                'application_id' => 16,
                'database_id' => 1,
            ],
        ]);

    }
}

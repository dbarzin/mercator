<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseMApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('database_m_application')->delete();

        \DB::table('database_m_application')->insert([
            0 => [
                'm_application_id' => 2,
                'database_id' => 3,
            ],
            1 => [
                'm_application_id' => 3,
                'database_id' => 4,
            ],
            2 => [
                'm_application_id' => 3,
                'database_id' => 1,
            ],
            3 => [
                'm_application_id' => 4,
                'database_id' => 5,
            ],
            4 => [
                'm_application_id' => 4,
                'database_id' => 6,
            ],
            5 => [
                'm_application_id' => 15,
                'database_id' => 5,
            ],
            6 => [
                'm_application_id' => 15,
                'database_id' => 4,
            ],
            7 => [
                'm_application_id' => 16,
                'database_id' => 1,
            ],
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationServiceMApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_service_m_application')->delete();

        \DB::table('application_service_m_application')->insert([
            0 => [
                'm_application_id' => 2,
                'application_service_id' => 3,
            ],
            1 => [
                'm_application_id' => 2,
                'application_service_id' => 4,
            ],
            2 => [
                'm_application_id' => 1,
                'application_service_id' => 3,
            ],
            3 => [
                'm_application_id' => 15,
                'application_service_id' => 2,
            ],
            4 => [
                'm_application_id' => 15,
                'application_service_id' => 3,
            ],
            5 => [
                'm_application_id' => 1,
                'application_service_id' => 1,
            ],
            6 => [
                'm_application_id' => 4,
                'application_service_id' => 11,
            ],
            7 => [
                'm_application_id' => 4,
                'application_service_id' => 5,
            ],
            8 => [
                'm_application_id' => 2,
                'application_service_id' => 7,
            ],
            9 => [
                'm_application_id' => 4,
                'application_service_id' => 7,
            ],
            10 => [
                'm_application_id' => 1,
                'application_service_id' => 10,
            ],
            11 => [
                'm_application_id' => 16,
                'application_service_id' => 10,
            ],
            12 => [
                'm_application_id' => 16,
                'application_service_id' => 11,
            ],
            13 => [
                'm_application_id' => 16,
                'application_service_id' => 5,
            ],
            14 => [
                'm_application_id' => 16,
                'application_service_id' => 6,
            ],
            15 => [
                'm_application_id' => 16,
                'application_service_id' => 7,
            ],
            16 => [
                'm_application_id' => 16,
                'application_service_id' => 9,
            ],
            17 => [
                'm_application_id' => 16,
                'application_service_id' => 1,
            ],
            18 => [
                'm_application_id' => 16,
                'application_service_id' => 2,
            ],
            19 => [
                'm_application_id' => 16,
                'application_service_id' => 3,
            ],
            20 => [
                'm_application_id' => 16,
                'application_service_id' => 4,
            ],
            21 => [
                'm_application_id' => 16,
                'application_service_id' => 8,
            ],
        ]);

    }
}

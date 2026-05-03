<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationServiceApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_application_service')->delete();

        \DB::table('application_application_service')->insert([
            0 => [
                'application_id' => 2,
                'application_service_id' => 3,
            ],
            1 => [
                'application_id' => 2,
                'application_service_id' => 4,
            ],
            2 => [
                'application_id' => 1,
                'application_service_id' => 3,
            ],
            3 => [
                'application_id' => 15,
                'application_service_id' => 2,
            ],
            4 => [
                'application_id' => 15,
                'application_service_id' => 3,
            ],
            5 => [
                'application_id' => 1,
                'application_service_id' => 1,
            ],
            6 => [
                'application_id' => 4,
                'application_service_id' => 11,
            ],
            7 => [
                'application_id' => 4,
                'application_service_id' => 5,
            ],
            8 => [
                'application_id' => 2,
                'application_service_id' => 7,
            ],
            9 => [
                'application_id' => 4,
                'application_service_id' => 7,
            ],
            10 => [
                'application_id' => 1,
                'application_service_id' => 10,
            ],
            11 => [
                'application_id' => 16,
                'application_service_id' => 10,
            ],
            12 => [
                'application_id' => 16,
                'application_service_id' => 11,
            ],
            13 => [
                'application_id' => 16,
                'application_service_id' => 5,
            ],
            14 => [
                'application_id' => 16,
                'application_service_id' => 6,
            ],
            15 => [
                'application_id' => 16,
                'application_service_id' => 7,
            ],
            16 => [
                'application_id' => 16,
                'application_service_id' => 9,
            ],
            17 => [
                'application_id' => 16,
                'application_service_id' => 1,
            ],
            18 => [
                'application_id' => 16,
                'application_service_id' => 2,
            ],
            19 => [
                'application_id' => 16,
                'application_service_id' => 3,
            ],
            20 => [
                'application_id' => 16,
                'application_service_id' => 4,
            ],
            21 => [
                'application_id' => 16,
                'application_service_id' => 8,
            ],
        ]);

    }
}

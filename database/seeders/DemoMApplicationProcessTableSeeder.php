<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMApplicationProcessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('m_application_process')->delete();

        \DB::table('m_application_process')->insert([
            0 => [
                'm_application_id' => 2,
                'process_id' => 1,
            ],
            1 => [
                'm_application_id' => 2,
                'process_id' => 2,
            ],
            2 => [
                'm_application_id' => 3,
                'process_id' => 2,
            ],
            3 => [
                'm_application_id' => 1,
                'process_id' => 1,
            ],
            4 => [
                'm_application_id' => 14,
                'process_id' => 2,
            ],
            5 => [
                'm_application_id' => 4,
                'process_id' => 3,
            ],
            6 => [
                'm_application_id' => 12,
                'process_id' => 4,
            ],
            7 => [
                'm_application_id' => 16,
                'process_id' => 1,
            ],
            8 => [
                'm_application_id' => 16,
                'process_id' => 2,
            ],
            9 => [
                'm_application_id' => 16,
                'process_id' => 3,
            ],
            10 => [
                'm_application_id' => 16,
                'process_id' => 4,
            ],
            11 => [
                'm_application_id' => 16,
                'process_id' => 9,
            ],
        ]);

    }
}

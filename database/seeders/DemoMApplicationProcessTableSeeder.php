<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationProcessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_process')->delete();

        \DB::table('application_process')->insert([
            0 => [
                'application_id' => 2,
                'process_id' => 1,
            ],
            1 => [
                'application_id' => 2,
                'process_id' => 2,
            ],
            2 => [
                'application_id' => 3,
                'process_id' => 2,
            ],
            3 => [
                'application_id' => 1,
                'process_id' => 1,
            ],
            4 => [
                'application_id' => 14,
                'process_id' => 2,
            ],
            5 => [
                'application_id' => 4,
                'process_id' => 3,
            ],
            6 => [
                'application_id' => 12,
                'process_id' => 4,
            ],
            7 => [
                'application_id' => 16,
                'process_id' => 1,
            ],
            8 => [
                'application_id' => 16,
                'process_id' => 2,
            ],
            9 => [
                'application_id' => 16,
                'process_id' => 3,
            ],
            10 => [
                'application_id' => 16,
                'process_id' => 4,
            ],
            11 => [
                'application_id' => 16,
                'process_id' => 9,
            ],
        ]);

    }
}

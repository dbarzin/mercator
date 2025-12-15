<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActivityProcessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('activity_process')->delete();

        \DB::table('activity_process')->insert([
            0 => [
                'process_id' => 1,
                'activity_id' => 1,
            ],
            1 => [
                'process_id' => 1,
                'activity_id' => 2,
            ],
            2 => [
                'process_id' => 2,
                'activity_id' => 3,
            ],
            3 => [
                'process_id' => 2,
                'activity_id' => 4,
            ],
            4 => [
                'process_id' => 3,
                'activity_id' => 2,
            ],
            5 => [
                'process_id' => 3,
                'activity_id' => 5,
            ],
            6 => [
                'process_id' => 4,
                'activity_id' => 5,
            ],
            7 => [
                'process_id' => 5,
                'activity_id' => 4,
            ],
            8 => [
                'process_id' => 6,
                'activity_id' => 4,
            ],
            9 => [
                'process_id' => 7,
                'activity_id' => 3,
            ],
            10 => [
                'process_id' => 8,
                'activity_id' => 4,
            ],
            11 => [
                'process_id' => 9,
                'activity_id' => 3,
            ],
            12 => [
                'process_id' => 1,
                'activity_id' => 5,
            ],
        ]);

    }
}

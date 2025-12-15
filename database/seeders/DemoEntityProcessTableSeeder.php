<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEntityProcessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('entity_process')->delete();

        \DB::table('entity_process')->insert([
            0 => [
                'process_id' => 1,
                'entity_id' => 1,
            ],
            1 => [
                'process_id' => 2,
                'entity_id' => 1,
            ],
            2 => [
                'process_id' => 3,
                'entity_id' => 1,
            ],
            3 => [
                'process_id' => 1,
                'entity_id' => 13,
            ],
            4 => [
                'process_id' => 3,
                'entity_id' => 13,
            ],
            5 => [
                'process_id' => 4,
                'entity_id' => 1,
            ],
            6 => [
                'process_id' => 7,
                'entity_id' => 3,
            ],
            7 => [
                'process_id' => 9,
                'entity_id' => 4,
            ],
            8 => [
                'process_id' => 2,
                'entity_id' => 8,
            ],
            9 => [
                'process_id' => 4,
                'entity_id' => 6,
            ],
            10 => [
                'process_id' => 4,
                'entity_id' => 7,
            ],
            11 => [
                'process_id' => 9,
                'entity_id' => 5,
            ],
            12 => [
                'process_id' => 1,
                'entity_id' => 9,
            ],
            13 => [
                'process_id' => 2,
                'entity_id' => 9,
            ],
            14 => [
                'process_id' => 3,
                'entity_id' => 9,
            ],
            15 => [
                'process_id' => 4,
                'entity_id' => 9,
            ],
            16 => [
                'process_id' => 9,
                'entity_id' => 9,
            ],
            17 => [
                'process_id' => 1,
                'entity_id' => 12,
            ],
            18 => [
                'process_id' => 1,
                'entity_id' => 2,
            ],
            19 => [
                'process_id' => 4,
                'entity_id' => 18,
            ],
            20 => [
                'process_id' => 3,
                'entity_id' => 19,
            ],
        ]);

    }
}

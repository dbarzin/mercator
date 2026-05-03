<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEntityApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_entity')->delete();

        \DB::table('application_entity')->insert([
            0 => [
                'application_id' => 2,
                'entity_id' => 1,
            ],
            1 => [
                'application_id' => 5,
                'entity_id' => 1,
            ],
            2 => [
                'application_id' => 7,
                'entity_id' => 2,
            ],
            3 => [
                'application_id' => 9,
                'entity_id' => 1,
            ],
            4 => [
                'application_id' => 10,
                'entity_id' => 1,
            ],
            5 => [
                'application_id' => 2,
                'entity_id' => 2,
            ],
            6 => [
                'application_id' => 11,
                'entity_id' => 1,
            ],
            7 => [
                'application_id' => 1,
                'entity_id' => 2,
            ],
            8 => [
                'application_id' => 1,
                'entity_id' => 8,
            ],
            9 => [
                'application_id' => 3,
                'entity_id' => 8,
            ],
            10 => [
                'application_id' => 4,
                'entity_id' => 8,
            ],
            11 => [
                'application_id' => 4,
                'entity_id' => 4,
            ],
            12 => [
                'application_id' => 16,
                'entity_id' => 2,
            ],
        ]);

    }
}

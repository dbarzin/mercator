<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseEntityTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('database_entity')->delete();

        \DB::table('database_entity')->insert([
            0 => [
                'database_id' => 1,
                'entity_id' => 1,
            ],
            1 => [
                'database_id' => 3,
                'entity_id' => 1,
            ],
            2 => [
                'database_id' => 4,
                'entity_id' => 1,
            ],
            3 => [
                'database_id' => 5,
                'entity_id' => 1,
            ],
            4 => [
                'database_id' => 6,
                'entity_id' => 1,
            ],
        ]);

    }
}

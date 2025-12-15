<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActivityOperationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('activity_operation')->delete();

        \DB::table('activity_operation')->insert([
            0 => [
                'activity_id' => 1,
                'operation_id' => 3,
            ],
            1 => [
                'activity_id' => 1,
                'operation_id' => 1,
            ],
            2 => [
                'activity_id' => 1,
                'operation_id' => 2,
            ],
            3 => [
                'activity_id' => 2,
                'operation_id' => 3,
            ],
            4 => [
                'activity_id' => 2,
                'operation_id' => 1,
            ],
            5 => [
                'activity_id' => 3,
                'operation_id' => 5,
            ],
            6 => [
                'activity_id' => 3,
                'operation_id' => 1,
            ],
            7 => [
                'activity_id' => 4,
                'operation_id' => 1,
            ],
            8 => [
                'activity_id' => 5,
                'operation_id' => 1,
            ],
        ]);

    }
}

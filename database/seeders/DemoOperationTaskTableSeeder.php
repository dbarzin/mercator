<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOperationTaskTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('operation_task')->delete();

        \DB::table('operation_task')->insert([
            0 => [
                'operation_id' => 1,
                'task_id' => 1,
            ],
            1 => [
                'operation_id' => 1,
                'task_id' => 2,
            ],
            2 => [
                'operation_id' => 2,
                'task_id' => 1,
            ],
            3 => [
                'operation_id' => 3,
                'task_id' => 3,
            ],
            4 => [
                'operation_id' => 4,
                'task_id' => 2,
            ],
            5 => [
                'operation_id' => 5,
                'task_id' => 1,
            ],
            6 => [
                'operation_id' => 5,
                'task_id' => 2,
            ],
            7 => [
                'operation_id' => 5,
                'task_id' => 3,
            ],
        ]);

    }
}

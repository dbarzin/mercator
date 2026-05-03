<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataProcessingApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_data_processing')->delete();

        \DB::table('application_data_processing')->insert([
            0 => [
                'data_processing_id' => 1,
                'application_id' => 15,
            ],
            1 => [
                'data_processing_id' => 1,
                'application_id' => 3,
            ],
            2 => [
                'data_processing_id' => 2,
                'application_id' => 1,
            ],
            3 => [
                'data_processing_id' => 4,
                'application_id' => 12,
            ],
        ]);

    }
}

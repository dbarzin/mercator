<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataProcessingInformationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('data_processing_information')->delete();

        \DB::table('data_processing_information')->insert([
            0 => [
                'data_processing_id' => 1,
                'information_id' => 4,
            ],
            1 => [
                'data_processing_id' => 1,
                'information_id' => 1,
            ],
            2 => [
                'data_processing_id' => 1,
                'information_id' => 2,
            ],
            3 => [
                'data_processing_id' => 2,
                'information_id' => 2,
            ],
            4 => [
                'data_processing_id' => 2,
                'information_id' => 3,
            ],
            5 => [
                'data_processing_id' => 4,
                'information_id' => 3,
            ],
        ]);

    }
}

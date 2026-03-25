<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoCertificateLogicalServerTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('certificate_logical_server')->delete();

        \DB::table('certificate_logical_server')->insert([
            0 => [
                'certificate_id' => 4,
                'logical_server_id' => 1,
            ],
            1 => [
                'certificate_id' => 5,
                'logical_server_id' => 2,
            ],
            2 => [
                'certificate_id' => 1,
                'logical_server_id' => 1,
            ],
            3 => [
                'certificate_id' => 2,
                'logical_server_id' => 1,
            ],
            4 => [
                'certificate_id' => 3,
                'logical_server_id' => 1,
            ],
            5 => [
                'certificate_id' => 7,
                'logical_server_id' => 1,
            ],
        ]);

    }
}

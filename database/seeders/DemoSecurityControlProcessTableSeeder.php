<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSecurityControlProcessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('security_control_process')->delete();

        \DB::table('security_control_process')->insert([
            0 => [
                'security_control_id' => 98,
                'process_id' => 1,
            ],
            1 => [
                'security_control_id' => 99,
                'process_id' => 1,
            ],
            2 => [
                'security_control_id' => 101,
                'process_id' => 1,
            ],
            3 => [
                'security_control_id' => 106,
                'process_id' => 1,
            ],
            4 => [
                'security_control_id' => 107,
                'process_id' => 1,
            ],
            5 => [
                'security_control_id' => 98,
                'process_id' => 2,
            ],
            6 => [
                'security_control_id' => 99,
                'process_id' => 2,
            ],
            7 => [
                'security_control_id' => 100,
                'process_id' => 2,
            ],
            8 => [
                'security_control_id' => 107,
                'process_id' => 2,
            ],
            9 => [
                'security_control_id' => 108,
                'process_id' => 2,
            ],
            10 => [
                'security_control_id' => 110,
                'process_id' => 2,
            ],
            11 => [
                'security_control_id' => 111,
                'process_id' => 2,
            ],
            12 => [
                'security_control_id' => 114,
                'process_id' => 2,
            ],
            13 => [
                'security_control_id' => 115,
                'process_id' => 2,
            ],
            14 => [
                'security_control_id' => 116,
                'process_id' => 2,
            ],
            15 => [
                'security_control_id' => 120,
                'process_id' => 2,
            ],
            16 => [
                'security_control_id' => 121,
                'process_id' => 2,
            ],
            17 => [
                'security_control_id' => 98,
                'process_id' => 4,
            ],
            18 => [
                'security_control_id' => 99,
                'process_id' => 4,
            ],
            19 => [
                'security_control_id' => 100,
                'process_id' => 4,
            ],
            20 => [
                'security_control_id' => 101,
                'process_id' => 4,
            ],
            21 => [
                'security_control_id' => 103,
                'process_id' => 4,
            ],
            22 => [
                'security_control_id' => 104,
                'process_id' => 4,
            ],
            23 => [
                'security_control_id' => 105,
                'process_id' => 4,
            ],
            24 => [
                'security_control_id' => 107,
                'process_id' => 4,
            ],
            25 => [
                'security_control_id' => 108,
                'process_id' => 4,
            ],
            26 => [
                'security_control_id' => 109,
                'process_id' => 4,
            ],
        ]);

    }
}

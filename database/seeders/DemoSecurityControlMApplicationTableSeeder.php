<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSecurityControlApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_security_control')->delete();

        \DB::table('application_security_control')->insert([
            0 => [
                'security_control_id' => 169,
                'application_id' => 2,
            ],
            1 => [
                'security_control_id' => 166,
                'application_id' => 2,
            ],
            2 => [
                'security_control_id' => 167,
                'application_id' => 2,
            ],
            3 => [
                'security_control_id' => 168,
                'application_id' => 2,
            ],
            4 => [
                'security_control_id' => 170,
                'application_id' => 2,
            ],
            5 => [
                'security_control_id' => 171,
                'application_id' => 2,
            ],
            6 => [
                'security_control_id' => 173,
                'application_id' => 2,
            ],
            7 => [
                'security_control_id' => 175,
                'application_id' => 2,
            ],
            8 => [
                'security_control_id' => 178,
                'application_id' => 2,
            ],
            9 => [
                'security_control_id' => 182,
                'application_id' => 2,
            ],
            10 => [
                'security_control_id' => 183,
                'application_id' => 2,
            ],
            11 => [
                'security_control_id' => 157,
                'application_id' => 3,
            ],
            12 => [
                'security_control_id' => 158,
                'application_id' => 3,
            ],
            13 => [
                'security_control_id' => 159,
                'application_id' => 3,
            ],
            14 => [
                'security_control_id' => 160,
                'application_id' => 3,
            ],
            15 => [
                'security_control_id' => 161,
                'application_id' => 3,
            ],
            16 => [
                'security_control_id' => 162,
                'application_id' => 3,
            ],
            17 => [
                'security_control_id' => 167,
                'application_id' => 3,
            ],
            18 => [
                'security_control_id' => 168,
                'application_id' => 3,
            ],
            19 => [
                'security_control_id' => 169,
                'application_id' => 3,
            ],
            20 => [
                'security_control_id' => 174,
                'application_id' => 3,
            ],
            21 => [
                'security_control_id' => 175,
                'application_id' => 3,
            ],
            22 => [
                'security_control_id' => 176,
                'application_id' => 3,
            ],
            23 => [
                'security_control_id' => 177,
                'application_id' => 3,
            ],
            24 => [
                'security_control_id' => 178,
                'application_id' => 3,
            ],
            25 => [
                'security_control_id' => 179,
                'application_id' => 3,
            ],
            26 => [
                'security_control_id' => 180,
                'application_id' => 3,
            ],
            27 => [
                'security_control_id' => 160,
                'application_id' => 18,
            ],
            28 => [
                'security_control_id' => 161,
                'application_id' => 18,
            ],
            29 => [
                'security_control_id' => 162,
                'application_id' => 18,
            ],
            30 => [
                'security_control_id' => 166,
                'application_id' => 18,
            ],
            31 => [
                'security_control_id' => 167,
                'application_id' => 18,
            ],
            32 => [
                'security_control_id' => 174,
                'application_id' => 18,
            ],
            33 => [
                'security_control_id' => 175,
                'application_id' => 18,
            ],
            34 => [
                'security_control_id' => 176,
                'application_id' => 18,
            ],
            35 => [
                'security_control_id' => 179,
                'application_id' => 18,
            ],
            36 => [
                'security_control_id' => 180,
                'application_id' => 18,
            ],
        ]);

    }
}

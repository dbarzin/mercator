<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoCertificateApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('application_certificate')->delete();

        \DB::table('application_certificate')->insert([
            0 => [
                'certificate_id' => 8,
                'application_id' => 4,
            ],
        ]);

    }
}

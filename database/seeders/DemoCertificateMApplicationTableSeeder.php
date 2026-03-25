<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoCertificateMApplicationTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('certificate_m_application')->delete();

        \DB::table('certificate_m_application')->insert([
            0 => [
                'certificate_id' => 8,
                'm_application_id' => 4,
            ],
        ]);

    }
}

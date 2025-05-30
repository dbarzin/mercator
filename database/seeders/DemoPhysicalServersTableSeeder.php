<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPhysicalServersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('physical_servers')->delete();

        \DB::table('physical_servers')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Server 01',
                'description' => '<p>Description of server 01</p>',
                'created_at' => '2025-01-01 00:00:00',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Server 02',
                'description' => '<p>Description of servers 02</p>',
                'created_at' => '2025-01-01 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
    }
}

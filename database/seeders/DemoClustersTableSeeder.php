<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoClustersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('clusters')->delete();

        \DB::table('clusters')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'CLUSTER 01',
                'description' => '<p>Description of cluster 01</p>',
                'type' => 'Super Cluster',
                'created_at' => '2025-01-01 00:00:00',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'CLUSTER 02',
                'description' => '<p>Description of cluster 02</p>',
                'type' => 'Super Cluster',
                'created_at' => '2025-01-01 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoForestAdsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('forest_ads')->delete();
        
        \DB::table('forest_ads')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'AD1',
                'description' => '<p>Foret de l\'AD 1</p>',
                'created_at' => '2020-07-03 09:50:07',
                'updated_at' => '2020-07-03 09:50:29',
                'deleted_at' => NULL,
                'zone_admin_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'AD2',
                'description' => '<p>Foret de l\'AD2</p>',
                'created_at' => '2020-07-03 09:50:19',
                'updated_at' => '2020-07-03 09:50:19',
                'deleted_at' => NULL,
                'zone_admin_id' => 1,
            ),
        ));
        
        
    }
}
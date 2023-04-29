<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoWansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wans')->delete();
        
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_clients')->delete();
        
        
        
    }
}
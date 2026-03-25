<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOauthPersonalAccessClientsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('oauth_personal_access_clients')->delete();

    }
}

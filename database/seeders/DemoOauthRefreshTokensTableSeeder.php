<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOauthRefreshTokensTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('oauth_refresh_tokens')->delete();

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOauthAuthCodesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('oauth_auth_codes')->delete();

    }
}

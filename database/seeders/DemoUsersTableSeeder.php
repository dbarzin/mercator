<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoUsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();

        \DB::table('users')->insert([
            0 => [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => null,
                'password' => '$2y$10$6KNtUi7G9Hsnt74LijQBtuQen1RbLzO71NpHsrSxrHmIUgDRhICQm',
                'remember_token' => null,
                'granularity' => 3,
                'language' => 'fr',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
            ],
        ]);

    }
}

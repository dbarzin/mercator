<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$6KNtUi7G9Hsnt74LijQBtuQen1RbLzO71NpHsrSxrHmIUgDRhICQm',
                'remember_token' => null,
                'granularity'    => 3,
                'language'       => 'fr'
            ],
        ];

        User::insert($users);
    }
}

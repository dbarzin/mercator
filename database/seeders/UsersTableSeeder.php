<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('users')->delete();

        $users = [
            [
                // No ID for PostgrSQL
                // 'id'             => 1,
                'login' => 'admin@admin.com',
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'granularity' => 3,
                'language' => 'fr',
            ],
        ];
        User::insert($users);
    }
}

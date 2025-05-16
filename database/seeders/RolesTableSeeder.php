<?php

namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {

        \DB::table('roles')->delete();

        $roles = [
            [
                'title' => 'Admin',
            ],
            [
                'title' => 'User',
            ],
            [
                'title' => 'Auditor',
            ],
            [
                'title' => 'Cartographer'
            ]
        ];

        Role::insert($roles);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Mercator\Core\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        \Log::info('RolesTableSeeder');
        
        if (DB::table('roles')->count() === 0) {

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
                    'title' => 'Cartographer',
                ],
            ];

            Role::query()->insert($roles);

            \Log::info('RolesTableSeeder: Roles inserted');
        }
    }
}

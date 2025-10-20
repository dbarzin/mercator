<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
\Log::info('RolesTableSeeder');
        if (DB::table('roles')->count() === 0) {

            $roles = [
                [
                    'id' => 1,
                    'title' => 'Admin',
                ],
                [
                    'id' => 2,
                    'title' => 'User',
                ],
                [
                    'id' => 3,
                    'title' => 'Auditor',
                ],
                [
                    'id' => 4,
                    'title' => 'Cartographer',
                ],
            ];

            Role::insert($roles);

            \Log::info('RolesTableSeeder: Roles inserted');;
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('role_user')->count() === 0) {

            $adminRoleId = DB::table('roles')->where('title', 'Admin')->value('id');
            $admin = User::query()
                ->where('login', 'admin@admin.com')
                ->first()
                ->roles()
                ->sync([$adminRoleId]);
        }
    }
}

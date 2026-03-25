<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('roles')->delete();

        \DB::table('roles')->insert([
            0 => [
                'id' => 1,
                'title' => 'Admin',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
            ],
            1 => [
                'id' => 2,
                'title' => 'User',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
            ],
            2 => [
                'id' => 3,
                'title' => 'Auditor',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
            ],
            3 => [
                'id' => 4,
                'title' => 'Cartographer',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
            ],
        ]);

    }
}

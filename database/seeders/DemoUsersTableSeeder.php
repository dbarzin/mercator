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
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$6KNtUi7G9Hsnt74LijQBtuQen1RbLzO71NpHsrSxrHmIUgDRhICQm',
                'remember_token' => NULL,
                'granularity' => 3,
                'language' => 'fr',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
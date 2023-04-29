<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'user_id' => 1,
                'role_id' => 1,
            ),
        ));
        
        
    }
}
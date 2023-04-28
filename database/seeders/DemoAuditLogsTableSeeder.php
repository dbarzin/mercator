<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAuditLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('audit_logs')->delete();
        
        
        
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEntityDocumentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('entity_document')->delete();
        
        
        
    }
}
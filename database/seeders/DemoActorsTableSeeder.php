<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoActorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('actors')->delete();
        
        \DB::table('actors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Jean',
                'nature' => 'Personne',
                'type' => 'interne',
                'contact' => 'jean@testdomain.org',
                'created_at' => '2020-06-14 13:02:22',
                'updated_at' => '2021-05-16 19:37:49',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Service 1',
                'nature' => 'Groupe',
                'type' => 'interne',
                'contact' => NULL,
                'created_at' => '2020-06-14 13:02:39',
                'updated_at' => '2020-06-17 16:43:42',
                'deleted_at' => '2020-06-17 16:43:42',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Service 2',
                'nature' => 'Groupe',
                'type' => 'Interne',
                'contact' => NULL,
                'created_at' => '2020-06-14 13:02:54',
                'updated_at' => '2020-06-17 16:43:46',
                'deleted_at' => '2020-06-17 16:43:46',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Pierre',
                'nature' => 'Personne',
                'type' => 'interne',
                'contact' => 'email : pierre@testdomain.com',
                'created_at' => '2020-06-17 16:44:01',
                'updated_at' => '2021-05-16 19:38:19',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Jacques',
                'nature' => 'personne',
                'type' => 'interne',
                'contact' => 'Téléphone 1234543423',
                'created_at' => '2020-06-17 16:44:23',
                'updated_at' => '2020-06-17 16:44:23',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Fournisseur 1',
                'nature' => 'entité',
                'type' => 'externe',
                'contact' => 'Tel : 1232 32312',
                'created_at' => '2020-06-17 16:44:50',
                'updated_at' => '2020-06-17 16:44:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
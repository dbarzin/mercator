<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_services')->delete();
        
        \DB::table('application_services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => '<p>Descrition du service applicatif 1</p>',
                'exposition' => 'cloud',
                'name' => 'SRV-1',
                'created_at' => '2020-06-13 11:35:31',
                'updated_at' => '2021-08-03 20:50:33',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => '<p>Description du service 2</p>',
                'exposition' => 'local',
                'name' => 'Service 2',
                'created_at' => '2020-06-13 11:35:48',
                'updated_at' => '2020-06-13 11:35:48',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => '<p>Description du service 3</p>',
                'exposition' => 'local',
                'name' => 'Service 3',
                'created_at' => '2020-06-13 11:36:04',
                'updated_at' => '2020-06-13 11:43:05',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => '<p>Description du service 4</p>',
                'exposition' => 'local',
                'name' => 'Service 4',
                'created_at' => '2020-06-13 11:36:17',
                'updated_at' => '2020-06-13 11:36:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => 'Extranet',
                'name' => 'SRV-4',
                'created_at' => '2021-08-02 16:11:43',
                'updated_at' => '2021-08-17 10:24:10',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => NULL,
                'name' => 'SRV-5',
                'created_at' => '2021-08-02 16:12:19',
                'updated_at' => '2021-08-02 16:12:19',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => NULL,
                'name' => 'SRV-6',
                'created_at' => '2021-08-02 16:12:56',
                'updated_at' => '2021-08-02 16:12:56',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => '<p>The service 99</p>',
                'exposition' => 'local',
                'name' => 'SRV-99',
                'created_at' => '2021-08-02 16:13:39',
                'updated_at' => '2021-09-07 18:53:36',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => NULL,
                'name' => 'SRV-9',
                'created_at' => '2021-08-02 16:14:27',
                'updated_at' => '2021-08-02 16:14:27',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => 'Extranet',
                'name' => 'SRV-10',
                'created_at' => '2021-08-02 16:15:21',
                'updated_at' => '2021-08-17 10:24:20',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => '<p>Service applicatif 4</p>',
                'exposition' => 'Extranet',
                'name' => 'SRV-11',
                'created_at' => '2021-08-02 16:16:34',
                'updated_at' => '2021-08-17 10:24:28',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
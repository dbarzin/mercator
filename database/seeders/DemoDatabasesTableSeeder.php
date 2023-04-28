<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('databases')->delete();
        
        \DB::table('databases')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Database 1',
                'description' => '<p>Description Database 1</p>',
                'responsible' => 'Paul',
                'type' => 'MySQL',
                'security_need_c' => 1,
                'external' => 'Interne',
                'created_at' => '2020-06-17 16:18:48',
                'updated_at' => '2021-05-14 12:19:45',
                'deleted_at' => NULL,
                'entity_resp_id' => 2,
                'security_need_i' => 2,
                'security_need_a' => 3,
                'security_need_t' => 4,
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Database 2',
                'description' => '<p>Description database 2</p>',
                'responsible' => 'Paul',
                'type' => 'MySQL',
                'security_need_c' => 1,
                'external' => 'Interne',
                'created_at' => '2020-06-17 16:19:24',
                'updated_at' => '2021-05-14 12:29:47',
                'deleted_at' => NULL,
                'entity_resp_id' => 1,
                'security_need_i' => 1,
                'security_need_a' => 1,
                'security_need_t' => 1,
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'MainDB',
                'description' => '<p>description de la base de données</p>',
                'responsible' => 'Paul',
                'type' => 'Oracle',
                'security_need_c' => 2,
                'external' => 'Interne',
                'created_at' => '2020-07-01 17:08:57',
                'updated_at' => '2021-08-20 03:52:23',
                'deleted_at' => NULL,
                'entity_resp_id' => 1,
                'security_need_i' => 2,
                'security_need_a' => 2,
                'security_need_t' => 2,
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'DB Compta',
                'description' => '<p>Base de donnée de la compta</p>',
                'responsible' => 'Paul',
                'type' => 'MariaDB',
                'security_need_c' => 2,
                'external' => 'Interne',
                'created_at' => '2020-08-24 17:58:23',
                'updated_at' => '2022-03-21 18:13:10',
                'deleted_at' => NULL,
                'entity_resp_id' => 18,
                'security_need_i' => 2,
                'security_need_a' => 2,
                'security_need_t' => 2,
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Data Warehouse',
                'description' => '<p>Base de données du datawarehouse</p>',
                'responsible' => 'Jean',
                'type' => 'Oracle',
                'security_need_c' => 2,
                'external' => 'Interne',
                'created_at' => '2021-05-14 12:24:02',
                'updated_at' => '2022-03-21 18:13:24',
                'deleted_at' => NULL,
                'entity_resp_id' => 1,
                'security_need_i' => 2,
                'security_need_a' => 2,
                'security_need_t' => 2,
            ),
        ));
        
        
    }
}
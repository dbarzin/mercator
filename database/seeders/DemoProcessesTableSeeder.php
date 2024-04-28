<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoProcessesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('processes')->delete();

        \DB::table('processes')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Processus 1',
                'description' => '<p>Description du processus 1</p>',
                'owner' => 'Ched',
                'security_need_c' => 3,
                'in_out' => '<ul><li>pommes</li><li>poires</li><li>cerise</li></ul><p>&lt;test</p>',
                'created_at' => '2020-06-17 16:36:24',
                'updated_at' => '2021-09-22 13:38:57',
                'deleted_at' => NULL,
                'macroprocess_id' => 1,
                'security_need_i' => 2,
                'security_need_a' => 3,
                'security_need_t' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Processus 2',
                'description' => '<p>Description du processus 2</p>',
                'owner' => 'Ched',
                'security_need_c' => 3,
                'in_out' => '<p>1 2 3 4 5 6</p>',
                'created_at' => '2020-06-17 16:36:58',
                'updated_at' => '2021-09-22 12:59:14',
                'deleted_at' => NULL,
                'macroprocess_id' => 10,
                'security_need_i' => 4,
                'security_need_a' => 2,
                'security_need_t' => 4,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Processus 3',
                'description' => '<p>Description du processus 3</p>',
                'owner' => 'Johan',
                'security_need_c' => 3,
                'in_out' => '<p>a,b,c</p><p>d,e,f</p>',
                'created_at' => '2020-07-01 17:50:27',
                'updated_at' => '2021-08-17 10:22:13',
                'deleted_at' => NULL,
                'macroprocess_id' => 2,
                'security_need_i' => 2,
                'security_need_a' => 3,
                'security_need_t' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Processus 4',
                'description' => '<p>Description du processus 4</p>',
                'owner' => 'Paul',
                'security_need_c' => 4,
                'in_out' => '<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>',
                'created_at' => '2020-08-18 17:00:36',
                'updated_at' => '2021-08-17 10:22:29',
                'deleted_at' => NULL,
                'macroprocess_id' => 2,
                'security_need_i' => 2,
                'security_need_a' => 2,
                'security_need_t' => 2,
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Processus 5',
                'description' => '<p>tto</p>',
                'owner' => NULL,
                'security_need_c' => 1,
                'in_out' => '<p>Bananes</p>',
                'created_at' => '2020-08-27 15:16:56',
                'updated_at' => '2020-08-27 15:17:01',
                'deleted_at' => '2020-08-27 15:17:01',
                'macroprocess_id' => 1,
                'security_need_i' => NULL,
                'security_need_a' => NULL,
                'security_need_t' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Processus 5',
                'description' => '<p>description de ptest</p>',
                'owner' => NULL,
                'security_need_c' => 0,
                'in_out' => '<p>toto titi tutu</p>',
                'created_at' => '2020-08-29 13:10:23',
                'updated_at' => '2020-08-29 13:10:28',
                'deleted_at' => '2020-08-29 13:10:28',
                'macroprocess_id' => NULL,
                'security_need_i' => NULL,
                'security_need_a' => NULL,
                'security_need_t' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Processus 6',
                'description' => '<p>Description goes here</p>',
                'owner' => NULL,
                'security_need_c' => 1,
                'in_out' => '<p>fdfsdfsd</p>',
                'created_at' => '2020-08-29 13:16:42',
                'updated_at' => '2020-08-29 13:17:09',
                'deleted_at' => '2020-08-29 13:17:09',
                'macroprocess_id' => 1,
                'security_need_i' => NULL,
                'security_need_a' => NULL,
                'security_need_t' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'ptest3',
                'description' => '<p>processus de test 3</p>',
                'owner' => 'CHEM - Facturation',
                'security_need_c' => 3,
                'in_out' => '<p>dsfsdf sdf sdf sd fsd fsd f s</p>',
                'created_at' => '2020-08-29 13:19:13',
                'updated_at' => '2020-08-29 13:20:59',
                'deleted_at' => '2020-08-29 13:20:59',
                'macroprocess_id' => 1,
                'security_need_i' => NULL,
                'security_need_a' => NULL,
                'security_need_t' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Processus 5',
                'description' => '<p>Description du cinquième processus</p>',
                'owner' => 'Paul',
                'security_need_c' => 4,
                'in_out' => '<ul><li>chat</li><li>chien</li><li>poisson</li></ul>',
                'created_at' => '2021-05-14 09:10:02',
                'updated_at' => '2021-09-22 12:59:14',
                'deleted_at' => NULL,
                'macroprocess_id' => 10,
                'security_need_i' => 3,
                'security_need_a' => 2,
                'security_need_t' => 3,
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'Proc 6',
                'description' => NULL,
                'owner' => NULL,
                'security_need_c' => 0,
                'in_out' => NULL,
                'created_at' => '2021-10-08 21:18:28',
                'updated_at' => '2021-10-08 21:28:38',
                'deleted_at' => '2021-10-08 21:28:38',
                'macroprocess_id' => NULL,
                'security_need_i' => 0,
                'security_need_a' => 0,
                'security_need_t' => 0,
            ),
        ));
    }
}

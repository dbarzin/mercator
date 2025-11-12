<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRelationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('relations')->delete();

        \DB::table('relations')->insert([
            0 => [
                'id' => 1,
                'importance' => 1,
                'name' => 'Membre',
                'type' => 'Fourniture de service',
                'description' => '<p>Here is the description of this relation</p>',
                'created_at' => '2020-05-21 00:49:47',
                'updated_at' => '2021-08-17 10:20:46',
                'deleted_at' => null,
                'source_id' => 1,
                'destination_id' => 6,
            ],
            1 => [
                'id' => 2,
                'importance' => 2,
                'name' => 'Membre',
                'type' => 'Fournisseur de service',
                'description' => '<p>Member description</p>',
                'created_at' => '2020-05-21 01:35:11',
                'updated_at' => '2021-09-19 13:12:19',
                'deleted_at' => null,
                'source_id' => 2,
                'destination_id' => 6,
            ],
            2 => [
                'id' => 3,
                'importance' => 1,
                'name' => 'Fournisseur',
                'type' => 'Fournisseur de service',
                'description' => '<p>description de la relation entre A et le B</p>',
                'created_at' => '2020-05-21 01:39:24',
                'updated_at' => '2021-08-17 10:20:59',
                'deleted_at' => null,
                'source_id' => 7,
                'destination_id' => 1,
            ],
            3 => [
                'id' => 4,
                'importance' => 2,
                'name' => 'Membre',
                'type' => 'Fourniture de service',
                'description' => '<p>Description du service</p>',
                'created_at' => '2020-05-21 04:23:03',
                'updated_at' => '2021-05-23 15:06:05',
                'deleted_at' => null,
                'source_id' => 2,
                'destination_id' => 6,
            ],
            4 => [
                'id' => 5,
                'importance' => 0,
                'name' => 'Membre',
                'type' => 'Fournisseur de service',
                'description' => null,
                'created_at' => '2020-05-21 04:23:35',
                'updated_at' => '2021-05-23 15:05:18',
                'deleted_at' => null,
                'source_id' => 2,
                'destination_id' => 6,
            ],
            5 => [
                'id' => 6,
                'importance' => 0,
                'name' => 'Fournisseur',
                'type' => 'fourniture de service',
                'description' => null,
                'created_at' => '2020-05-21 04:24:35',
                'updated_at' => '2020-05-21 04:24:35',
                'deleted_at' => null,
                'source_id' => 7,
                'destination_id' => 2,
            ],
            6 => [
                'id' => 7,
                'importance' => 0,
                'name' => 'Membre',
                'type' => 'fourniture de service',
                'description' => null,
                'created_at' => '2020-05-21 04:26:43',
                'updated_at' => '2020-05-21 04:26:43',
                'deleted_at' => null,
                'source_id' => 4,
                'destination_id' => 6,
            ],
            7 => [
                'id' => 8,
                'importance' => 3,
                'name' => 'Rapporte',
                'type' => null,
                'description' => null,
                'created_at' => '2020-05-21 04:32:19',
                'updated_at' => '2020-07-05 12:10:01',
                'deleted_at' => null,
                'source_id' => 1,
                'destination_id' => 5,
            ],
            8 => [
                'id' => 9,
                'importance' => 0,
                'name' => 'Fournisseur',
                'type' => 'fourniture de service',
                'description' => null,
                'created_at' => '2020-05-21 04:33:33',
                'updated_at' => '2020-05-21 04:33:33',
                'deleted_at' => null,
                'source_id' => 9,
                'destination_id' => 1,
            ],
            9 => [
                'id' => 10,
                'importance' => 2,
                'name' => 'Rapporte',
                'type' => 'Fournisseur de service',
                'description' => '<p>Régelement général APD34</p>',
                'created_at' => '2020-05-22 23:21:02',
                'updated_at' => '2020-08-24 16:31:29',
                'deleted_at' => null,
                'source_id' => 1,
                'destination_id' => 8,
            ],
            10 => [
                'id' => 11,
                'importance' => 2,
                'name' => 'toto',
                'type' => null,
                'description' => null,
                'created_at' => '2020-07-05 12:14:15',
                'updated_at' => '2020-07-05 12:14:55',
                'deleted_at' => '2020-07-05 12:14:55',
                'source_id' => 3,
                'destination_id' => 2,
            ],
            11 => [
                'id' => 12,
                'importance' => 1,
                'name' => 'Fournisseur',
                'type' => 'Fournisseur de service',
                'description' => '<p>Analyse de risques</p>',
                'created_at' => '2020-08-24 16:23:30',
                'updated_at' => '2020-08-24 16:23:48',
                'deleted_at' => null,
                'source_id' => 2,
                'destination_id' => 4,
            ],
            12 => [
                'id' => 13,
                'importance' => 1,
                'name' => 'Fournisseur',
                'type' => 'Fourniture de service',
                'description' => '<p>Description du service</p>',
                'created_at' => '2020-10-14 19:06:24',
                'updated_at' => '2021-05-23 15:06:34',
                'deleted_at' => null,
                'source_id' => 2,
                'destination_id' => 12,
            ],
        ]);

    }
}

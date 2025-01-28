<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'user_management_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'permission_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'permission_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'title' => 'permission_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'title' => 'permission_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'title' => 'permission_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'title' => 'role_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'title' => 'role_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'title' => 'role_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'title' => 'role_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'title' => 'role_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'title' => 'user_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'title' => 'user_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'title' => 'user_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'title' => 'user_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'title' => 'user_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'title' => 'entity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'title' => 'entity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 =>
            array (
                'id' => 19,
                'title' => 'entity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array (
                'id' => 20,
                'title' => 'entity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array (
                'id' => 21,
                'title' => 'entity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array (
                'id' => 22,
                'title' => 'ecosystem_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array (
                'id' => 23,
                'title' => 'relation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array (
                'id' => 24,
                'title' => 'relation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array (
                'id' => 25,
                'title' => 'relation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 =>
            array (
                'id' => 26,
                'title' => 'relation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 =>
            array (
                'id' => 27,
                'title' => 'relation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 =>
            array (
                'id' => 28,
                'title' => 'process_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 =>
            array (
                'id' => 29,
                'title' => 'process_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 =>
            array (
                'id' => 30,
                'title' => 'process_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 =>
            array (
                'id' => 31,
                'title' => 'process_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array (
                'id' => 32,
                'title' => 'process_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array (
                'id' => 33,
                'title' => 'metier_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array (
                'id' => 34,
                'title' => 'operation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array (
                'id' => 35,
                'title' => 'operation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array (
                'id' => 36,
                'title' => 'operation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array (
                'id' => 37,
                'title' => 'operation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 =>
            array (
                'id' => 38,
                'title' => 'operation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 =>
            array (
                'id' => 39,
                'title' => 'actor_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 =>
            array (
                'id' => 40,
                'title' => 'actor_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 =>
            array (
                'id' => 41,
                'title' => 'actor_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array (
                'id' => 42,
                'title' => 'actor_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 =>
            array (
                'id' => 43,
                'title' => 'actor_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 =>
            array (
                'id' => 44,
                'title' => 'activity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 =>
            array (
                'id' => 45,
                'title' => 'activity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 =>
            array (
                'id' => 46,
                'title' => 'activity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 =>
            array (
                'id' => 47,
                'title' => 'activity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 =>
            array (
                'id' => 48,
                'title' => 'activity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 =>
            array (
                'id' => 49,
                'title' => 'task_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 =>
            array (
                'id' => 50,
                'title' => 'task_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 =>
            array (
                'id' => 51,
                'title' => 'task_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 =>
            array (
                'id' => 52,
                'title' => 'task_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 =>
            array (
                'id' => 53,
                'title' => 'task_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 =>
            array (
                'id' => 54,
                'title' => 'information_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 =>
            array (
                'id' => 55,
                'title' => 'information_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 =>
            array (
                'id' => 56,
                'title' => 'information_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 =>
            array (
                'id' => 57,
                'title' => 'information_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 =>
            array (
                'id' => 58,
                'title' => 'information_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 =>
            array (
                'id' => 59,
                'title' => 'application_block_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 =>
            array (
                'id' => 60,
                'title' => 'application_block_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 =>
            array (
                'id' => 61,
                'title' => 'application_block_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 =>
            array (
                'id' => 62,
                'title' => 'application_block_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 =>
            array (
                'id' => 63,
                'title' => 'application_block_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 =>
            array (
                'id' => 64,
                'title' => 'application_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 =>
            array (
                'id' => 65,
                'title' => 'application_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            65 =>
            array (
                'id' => 66,
                'title' => 'application_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 =>
            array (
                'id' => 67,
                'title' => 'application_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 =>
            array (
                'id' => 68,
                'title' => 'application_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 =>
            array (
                'id' => 69,
                'title' => 'papplication_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            69 =>
            array (
                'id' => 70,
                'title' => 'm_application_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            70 =>
            array (
                'id' => 71,
                'title' => 'm_application_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            71 =>
            array (
                'id' => 72,
                'title' => 'm_application_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            72 =>
            array (
                'id' => 73,
                'title' => 'm_application_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            73 =>
            array (
                'id' => 74,
                'title' => 'm_application_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            74 =>
            array (
                'id' => 75,
                'title' => 'application_service_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            75 =>
            array (
                'id' => 76,
                'title' => 'application_service_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            76 =>
            array (
                'id' => 77,
                'title' => 'application_service_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            77 =>
            array (
                'id' => 78,
                'title' => 'application_service_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            78 =>
            array (
                'id' => 79,
                'title' => 'application_service_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            79 =>
            array (
                'id' => 80,
                'title' => 'database_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            80 =>
            array (
                'id' => 81,
                'title' => 'database_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            81 =>
            array (
                'id' => 82,
                'title' => 'database_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            82 =>
            array (
                'id' => 83,
                'title' => 'database_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            83 =>
            array (
                'id' => 84,
                'title' => 'database_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            84 =>
            array (
                'id' => 85,
                'title' => 'flux_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            85 =>
            array (
                'id' => 86,
                'title' => 'flux_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            86 =>
            array (
                'id' => 87,
                'title' => 'flux_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            87 =>
            array (
                'id' => 88,
                'title' => 'flux_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            88 =>
            array (
                'id' => 89,
                'title' => 'flux_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            89 =>
            array (
                'id' => 90,
                'title' => 'zone_admin_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            90 =>
            array (
                'id' => 91,
                'title' => 'zone_admin_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            91 =>
            array (
                'id' => 92,
                'title' => 'zone_admin_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            92 =>
            array (
                'id' => 93,
                'title' => 'zone_admin_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            93 =>
            array (
                'id' => 94,
                'title' => 'zone_admin_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            94 =>
            array (
                'id' => 95,
                'title' => 'administration_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            95 =>
            array (
                'id' => 96,
                'title' => 'annuaire_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            96 =>
            array (
                'id' => 97,
                'title' => 'annuaire_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            97 =>
            array (
                'id' => 98,
                'title' => 'annuaire_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            98 =>
            array (
                'id' => 99,
                'title' => 'annuaire_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            99 =>
            array (
                'id' => 100,
                'title' => 'annuaire_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            100 =>
            array (
                'id' => 101,
                'title' => 'forest_ad_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            101 =>
            array (
                'id' => 102,
                'title' => 'forest_ad_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            102 =>
            array (
                'id' => 103,
                'title' => 'forest_ad_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            103 =>
            array (
                'id' => 104,
                'title' => 'forest_ad_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            104 =>
            array (
                'id' => 105,
                'title' => 'forest_ad_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            105 =>
            array (
                'id' => 106,
                'title' => 'domaine_ad_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            106 =>
            array (
                'id' => 107,
                'title' => 'domaine_ad_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            107 =>
            array (
                'id' => 108,
                'title' => 'domaine_ad_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            108 =>
            array (
                'id' => 109,
                'title' => 'domaine_ad_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            109 =>
            array (
                'id' => 110,
                'title' => 'domaine_ad_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            110 =>
            array (
                'id' => 111,
                'title' => 'infrastructure_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            111 =>
            array (
                'id' => 112,
                'title' => 'network_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            112 =>
            array (
                'id' => 113,
                'title' => 'network_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            113 =>
            array (
                'id' => 114,
                'title' => 'network_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            114 =>
            array (
                'id' => 115,
                'title' => 'network_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            115 =>
            array (
                'id' => 116,
                'title' => 'network_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            116 =>
            array (
                'id' => 117,
                'title' => 'subnetwork_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            117 =>
            array (
                'id' => 118,
                'title' => 'subnetwork_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            118 =>
            array (
                'id' => 119,
                'title' => 'subnetwork_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            119 =>
            array (
                'id' => 120,
                'title' => 'subnetwork_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            120 =>
            array (
                'id' => 121,
                'title' => 'subnetwork_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            121 =>
            array (
                'id' => 122,
                'title' => 'gateway_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            122 =>
            array (
                'id' => 123,
                'title' => 'gateway_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            123 =>
            array (
                'id' => 124,
                'title' => 'gateway_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            124 =>
            array (
                'id' => 125,
                'title' => 'gateway_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            125 =>
            array (
                'id' => 126,
                'title' => 'gateway_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            126 =>
            array (
                'id' => 127,
                'title' => 'external_connected_entity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            127 =>
            array (
                'id' => 128,
                'title' => 'external_connected_entity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            128 =>
            array (
                'id' => 129,
                'title' => 'external_connected_entity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            129 =>
            array (
                'id' => 130,
                'title' => 'external_connected_entity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            130 =>
            array (
                'id' => 131,
                'title' => 'external_connected_entity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            131 =>
            array (
                'id' => 132,
                'title' => 'network_switch_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            132 =>
            array (
                'id' => 133,
                'title' => 'network_switch_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            133 =>
            array (
                'id' => 134,
                'title' => 'network_switch_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            134 =>
            array (
                'id' => 135,
                'title' => 'network_switch_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            135 =>
            array (
                'id' => 136,
                'title' => 'network_switch_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            136 =>
            array (
                'id' => 137,
                'title' => 'router_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            137 =>
            array (
                'id' => 138,
                'title' => 'router_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            138 =>
            array (
                'id' => 139,
                'title' => 'router_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            139 =>
            array (
                'id' => 140,
                'title' => 'router_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            140 =>
            array (
                'id' => 141,
                'title' => 'router_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            141 =>
            array (
                'id' => 142,
                'title' => 'security_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            142 =>
            array (
                'id' => 143,
                'title' => 'security_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            143 =>
            array (
                'id' => 144,
                'title' => 'security_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            144 =>
            array (
                'id' => 145,
                'title' => 'security_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            145 =>
            array (
                'id' => 146,
                'title' => 'security_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            146 =>
            array (
                'id' => 147,
                'title' => 'dhcp_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            147 =>
            array (
                'id' => 148,
                'title' => 'dhcp_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            148 =>
            array (
                'id' => 149,
                'title' => 'dhcp_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            149 =>
            array (
                'id' => 150,
                'title' => 'dhcp_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            150 =>
            array (
                'id' => 151,
                'title' => 'dhcp_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            151 =>
            array (
                'id' => 152,
                'title' => 'dnsserver_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            152 =>
            array (
                'id' => 153,
                'title' => 'dnsserver_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            153 =>
            array (
                'id' => 154,
                'title' => 'dnsserver_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            154 =>
            array (
                'id' => 155,
                'title' => 'dnsserver_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            155 =>
            array (
                'id' => 156,
                'title' => 'dnsserver_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            156 =>
            array (
                'id' => 157,
                'title' => 'logical_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            157 =>
            array (
                'id' => 158,
                'title' => 'logical_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            158 =>
            array (
                'id' => 159,
                'title' => 'logical_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            159 =>
            array (
                'id' => 160,
                'title' => 'logical_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            160 =>
            array (
                'id' => 161,
                'title' => 'logical_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            161 =>
            array (
                'id' => 162,
                'title' => 'physicalinfrastructure_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            162 =>
            array (
                'id' => 163,
                'title' => 'site_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            163 =>
            array (
                'id' => 164,
                'title' => 'site_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            164 =>
            array (
                'id' => 165,
                'title' => 'site_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            165 =>
            array (
                'id' => 166,
                'title' => 'site_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            166 =>
            array (
                'id' => 167,
                'title' => 'site_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            167 =>
            array (
                'id' => 168,
                'title' => 'building_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            168 =>
            array (
                'id' => 169,
                'title' => 'building_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            169 =>
            array (
                'id' => 170,
                'title' => 'building_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            170 =>
            array (
                'id' => 171,
                'title' => 'building_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            171 =>
            array (
                'id' => 172,
                'title' => 'building_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            172 =>
            array (
                'id' => 173,
                'title' => 'bay_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            173 =>
            array (
                'id' => 174,
                'title' => 'bay_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            174 =>
            array (
                'id' => 175,
                'title' => 'bay_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 =>
            array (
                'id' => 176,
                'title' => 'bay_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 =>
            array (
                'id' => 177,
                'title' => 'bay_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 =>
            array (
                'id' => 178,
                'title' => 'physical_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 =>
            array (
                'id' => 179,
                'title' => 'physical_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 =>
            array (
                'id' => 180,
                'title' => 'physical_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 =>
            array (
                'id' => 181,
                'title' => 'physical_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 =>
            array (
                'id' => 182,
                'title' => 'physical_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 =>
            array (
                'id' => 183,
                'title' => 'workstation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 =>
            array (
                'id' => 184,
                'title' => 'workstation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 =>
            array (
                'id' => 185,
                'title' => 'workstation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 =>
            array (
                'id' => 186,
                'title' => 'workstation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 =>
            array (
                'id' => 187,
                'title' => 'workstation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 =>
            array (
                'id' => 188,
                'title' => 'storage_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 =>
            array (
                'id' => 189,
                'title' => 'storage_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 =>
            array (
                'id' => 190,
                'title' => 'storage_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 =>
            array (
                'id' => 191,
                'title' => 'storage_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 =>
            array (
                'id' => 192,
                'title' => 'storage_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            192 =>
            array (
                'id' => 193,
                'title' => 'peripheral_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            193 =>
            array (
                'id' => 194,
                'title' => 'peripheral_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            194 =>
            array (
                'id' => 195,
                'title' => 'peripheral_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            195 =>
            array (
                'id' => 196,
                'title' => 'peripheral_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            196 =>
            array (
                'id' => 197,
                'title' => 'peripheral_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            197 =>
            array (
                'id' => 198,
                'title' => 'phone_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            198 =>
            array (
                'id' => 199,
                'title' => 'phone_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 =>
            array (
                'id' => 200,
                'title' => 'phone_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 =>
            array (
                'id' => 201,
                'title' => 'phone_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 =>
            array (
                'id' => 202,
                'title' => 'phone_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 =>
            array (
                'id' => 203,
                'title' => 'physical_switch_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 =>
            array (
                'id' => 204,
                'title' => 'physical_switch_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 =>
            array (
                'id' => 205,
                'title' => 'physical_switch_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            205 =>
            array (
                'id' => 206,
                'title' => 'physical_switch_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            206 =>
            array (
                'id' => 207,
                'title' => 'physical_switch_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            207 =>
            array (
                'id' => 208,
                'title' => 'physical_router_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            208 =>
            array (
                'id' => 209,
                'title' => 'physical_router_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            209 =>
            array (
                'id' => 210,
                'title' => 'physical_router_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            210 =>
            array (
                'id' => 211,
                'title' => 'physical_router_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 =>
            array (
                'id' => 212,
                'title' => 'physical_router_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 =>
            array (
                'id' => 213,
                'title' => 'wifi_terminal_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 =>
            array (
                'id' => 214,
                'title' => 'wifi_terminal_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 =>
            array (
                'id' => 215,
                'title' => 'wifi_terminal_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            215 =>
            array (
                'id' => 216,
                'title' => 'wifi_terminal_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            216 =>
            array (
                'id' => 217,
                'title' => 'wifi_terminal_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 =>
            array (
                'id' => 218,
                'title' => 'physical_security_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 =>
            array (
                'id' => 219,
                'title' => 'physical_security_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 =>
            array (
                'id' => 220,
                'title' => 'physical_security_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 =>
            array (
                'id' => 221,
                'title' => 'physical_security_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 =>
            array (
                'id' => 222,
                'title' => 'physical_security_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 =>
            array (
                'id' => 223,
                'title' => 'wan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 =>
            array (
                'id' => 224,
                'title' => 'wan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 =>
            array (
                'id' => 225,
                'title' => 'wan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            225 =>
            array (
                'id' => 226,
                'title' => 'wan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            226 =>
            array (
                'id' => 227,
                'title' => 'wan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            227 =>
            array (
                'id' => 228,
                'title' => 'man_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            228 =>
            array (
                'id' => 229,
                'title' => 'man_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            229 =>
            array (
                'id' => 230,
                'title' => 'man_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            230 =>
            array (
                'id' => 231,
                'title' => 'man_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            231 =>
            array (
                'id' => 232,
                'title' => 'man_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            232 =>
            array (
                'id' => 233,
                'title' => 'lan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            233 =>
            array (
                'id' => 234,
                'title' => 'lan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            234 =>
            array (
                'id' => 235,
                'title' => 'lan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            235 =>
            array (
                'id' => 236,
                'title' => 'lan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            236 =>
            array (
                'id' => 237,
                'title' => 'lan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            237 =>
            array (
                'id' => 238,
                'title' => 'vlan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 =>
            array (
                'id' => 239,
                'title' => 'vlan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            239 =>
            array (
                'id' => 240,
                'title' => 'vlan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            240 =>
            array (
                'id' => 241,
                'title' => 'vlan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            241 =>
            array (
                'id' => 242,
                'title' => 'vlan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            242 =>
            array (
                'id' => 243,
                'title' => 'application_module_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            243 =>
            array (
                'id' => 244,
                'title' => 'application_module_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            244 =>
            array (
                'id' => 245,
                'title' => 'application_module_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            245 =>
            array (
                'id' => 246,
                'title' => 'application_module_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            246 =>
            array (
                'id' => 247,
                'title' => 'application_module_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            247 =>
            array (
                'id' => 248,
                'title' => 'audit_log_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            248 =>
            array (
                'id' => 249,
                'title' => 'audit_log_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            249 =>
            array (
                'id' => 250,
                'title' => 'macro_processus_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            250 =>
            array (
                'id' => 251,
                'title' => 'macro_processus_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            251 =>
            array (
                'id' => 252,
                'title' => 'macro_processus_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            252 =>
            array (
                'id' => 253,
                'title' => 'macro_processus_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            253 =>
            array (
                'id' => 254,
                'title' => 'macro_processus_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            254 =>
            array (
                'id' => 255,
                'title' => 'configuration_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            255 =>
            array (
                'id' => 256,
                'title' => 'profile_password_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            256 =>
            array (
                'id' => 257,
                'title' => 'certificate_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            257 =>
            array (
                'id' => 258,
                'title' => 'certificate_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            258 =>
            array (
                'id' => 259,
                'title' => 'certificate_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            259 =>
            array (
                'id' => 260,
                'title' => 'certificate_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            260 =>
            array (
                'id' => 261,
                'title' => 'certificate_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            261 =>
            array (
                'id' => 262,
                'title' => 'configure',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            262 =>
            array (
                'id' => 263,
                'title' => 'physical_link_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            263 =>
            array (
                'id' => 264,
                'title' => 'physical_link_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            264 =>
            array (
                'id' => 265,
                'title' => 'physical_link_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            265 =>
            array (
                'id' => 266,
                'title' => 'physical_link_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            266 =>
            array (
                'id' => 267,
                'title' => 'physical_link_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            267 =>
            array (
                'id' => 268,
                'title' => 'gdpr_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            268 =>
            array (
                'id' => 269,
                'title' => 'security_controls_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            269 =>
            array (
                'id' => 270,
                'title' => 'security_controls_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            270 =>
            array (
                'id' => 271,
                'title' => 'security_controls_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            271 =>
            array (
                'id' => 272,
                'title' => 'security_controls_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            272 =>
            array (
                'id' => 273,
                'title' => 'security_controls_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            273 =>
            array (
                'id' => 274,
                'title' => 'data_processing_register_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            274 =>
            array (
                'id' => 275,
                'title' => 'data_processing_register_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            275 =>
            array (
                'id' => 276,
                'title' => 'data_processing_register_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            276 =>
            array (
                'id' => 277,
                'title' => 'data_processing_register_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            277 =>
            array (
                'id' => 278,
                'title' => 'data_processing_register_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            278 =>
            array (
                'id' => 279,
                'title' => 'patching_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            279 =>
            array (
                'id' => 280,
                'title' => 'patching_make',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            280 =>
            array (
                'id' => 281,
                'title' => 'cluster_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            281 =>
            array (
                'id' => 282,
                'title' => 'cluster_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            282 =>
            array (
                'id' => 283,
                'title' => 'cluster_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            283 =>
            array (
                'id' => 284,
                'title' => 'cluster_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            284 =>
            array (
                'id' => 285,
                'title' => 'cluster_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            285 =>
            array (
                'id' => 286,
                'title' => 'logical_flow_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            286 =>
            array (
                'id' => 287,
                'title' => 'logical_flow_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            287 =>
            array (
                'id' => 288,
                'title' => 'logical_flow_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            288 =>
            array (
                'id' => 289,
                'title' => 'logical_flow_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 =>
            array (
                'id' => 290,
                'title' => 'logical_flow_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            290 =>
            array (
                'id' => 291,
                'title' => 'admin_user_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            291 =>
            array (
                'id' => 292,
                'title' => 'admin_user_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            292 =>
            array (
                'id' => 293,
                'title' => 'admin_user_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            293 =>
            array (
                'id' => 294,
                'title' => 'admin_user_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            294 =>
            array (
                'id' => 295,
                'title' => 'admin_user_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            295 =>
            array (
                'id' => 296,
                'title' => 'graph_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            296 =>
            array (
                'id' => 297,
                'title' => 'graph_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            297 =>
            array (
                'id' => 298,
                'title' => 'graph_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            298 =>
            array (
                'id' => 299,
                'title' => 'graph_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            299 =>
            array (
                'id' => 300,
                'title' => 'graph_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            300 =>
            array (
                'id' => 301,
                'title' => 'container_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            301 =>
            array (
                'id' => 302,
                'title' => 'container_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            302 =>
            array (
                'id' => 303,
                'title' => 'container_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            303 =>
            array (
                'id' => 304,
                'title' => 'container_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            304 =>
            array (
                'id' => 305,
                'title' => 'container_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));


    }
}

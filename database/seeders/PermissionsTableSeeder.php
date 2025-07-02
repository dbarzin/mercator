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
                'title' => 'user_management_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'title' => 'permission_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'title' => 'permission_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'title' => 'permission_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'title' => 'permission_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'title' => 'permission_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array (
                'title' => 'role_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array (
                'title' => 'role_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array (
                'title' => 'role_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array (
                'title' => 'role_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array (
                'title' => 'role_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array (
                'title' => 'user_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array (
                'title' => 'user_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array (
                'title' => 'user_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array (
                'title' => 'user_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array (
                'title' => 'user_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array (
                'title' => 'entity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array (
                'title' => 'entity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 =>
            array (
                'title' => 'entity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array (
                'title' => 'entity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array (
                'title' => 'entity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array (
                'title' => 'ecosystem_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array (
                'title' => 'relation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array (
                'title' => 'relation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array (
                'title' => 'relation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 =>
            array (
                'title' => 'relation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 =>
            array (
                'title' => 'relation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 =>
            array (
                'title' => 'process_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 =>
            array (
                'title' => 'process_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 =>
            array (
                'title' => 'process_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 =>
            array (
                'title' => 'process_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array (
                'title' => 'process_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array (
                'title' => 'metier_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array (
                'title' => 'operation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array (
                'title' => 'operation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array (
                'title' => 'operation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array (
                'title' => 'operation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 =>
            array (
                'title' => 'operation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 =>
            array (
                'title' => 'actor_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 =>
            array (
                'title' => 'actor_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 =>
            array (
                'title' => 'actor_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array (
                'title' => 'actor_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 =>
            array (
                'title' => 'actor_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 =>
            array (
                'title' => 'activity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 =>
            array (
                'title' => 'activity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 =>
            array (
                'title' => 'activity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 =>
            array (
                'title' => 'activity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 =>
            array (
                'title' => 'activity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 =>
            array (
                'title' => 'task_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 =>
            array (
                'title' => 'task_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 =>
            array (
                'title' => 'task_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 =>
            array (
                'title' => 'task_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 =>
            array (
                'title' => 'task_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 =>
            array (
                'title' => 'information_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 =>
            array (
                'title' => 'information_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 =>
            array (
                'title' => 'information_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 =>
            array (
                'title' => 'information_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 =>
            array (
                'title' => 'information_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 =>
            array (
                'title' => 'application_block_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 =>
            array (
                'title' => 'application_block_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 =>
            array (
                'title' => 'application_block_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 =>
            array (
                'title' => 'application_block_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 =>
            array (
                'title' => 'application_block_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 =>
            array (
                'title' => 'application_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 =>
            array (
                'title' => 'application_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            65 =>
            array (
                'title' => 'application_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 =>
            array (
                'title' => 'application_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 =>
            array (
                'title' => 'application_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 =>
            array (
                'title' => 'papplication_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            69 =>
            array (
                'title' => 'm_application_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            70 =>
            array (
                'title' => 'm_application_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            71 =>
            array (
                'title' => 'm_application_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            72 =>
            array (
                'title' => 'm_application_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            73 =>
            array (
                'title' => 'm_application_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            74 =>
            array (
                'title' => 'application_service_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            75 =>
            array (
                'title' => 'application_service_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            76 =>
            array (
                'title' => 'application_service_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            77 =>
            array (
                'title' => 'application_service_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            78 =>
            array (
                'title' => 'application_service_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            79 =>
            array (
                'title' => 'database_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            80 =>
            array (
                'title' => 'database_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            81 =>
            array (
                'title' => 'database_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            82 =>
            array (
                'title' => 'database_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            83 =>
            array (
                'title' => 'database_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            84 =>
            array (
                'title' => 'flux_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            85 =>
            array (
                'title' => 'flux_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            86 =>
            array (
                'title' => 'flux_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            87 =>
            array (
                'title' => 'flux_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            88 =>
            array (
                'title' => 'flux_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            89 =>
            array (
                'title' => 'zone_admin_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            90 =>
            array (
                'title' => 'zone_admin_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            91 =>
            array (
                'title' => 'zone_admin_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            92 =>
            array (
                'title' => 'zone_admin_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            93 =>
            array (
                'title' => 'zone_admin_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            94 =>
            array (
                'title' => 'administration_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            95 =>
            array (
                'title' => 'annuaire_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            96 =>
            array (
                'title' => 'annuaire_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            97 =>
            array (
                'title' => 'annuaire_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            98 =>
            array (
                'title' => 'annuaire_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            99 =>
            array (
                'title' => 'annuaire_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            100 =>
            array (
                'title' => 'forest_ad_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            101 =>
            array (
                'title' => 'forest_ad_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            102 =>
            array (
                'title' => 'forest_ad_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            103 =>
            array (
                'title' => 'forest_ad_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            104 =>
            array (
                'title' => 'forest_ad_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            105 =>
            array (
                'title' => 'domaine_ad_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            106 =>
            array (
                'title' => 'domaine_ad_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            107 =>
            array (
                'title' => 'domaine_ad_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            108 =>
            array (
                'title' => 'domaine_ad_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            109 =>
            array (
                'title' => 'domaine_ad_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            110 =>
            array (
                'title' => 'infrastructure_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            111 =>
            array (
                'title' => 'network_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            112 =>
            array (
                'title' => 'network_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            113 =>
            array (
                'title' => 'network_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            114 =>
            array (
                'title' => 'network_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            115 =>
            array (
                'title' => 'network_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            116 =>
            array (
                'title' => 'subnetwork_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            117 =>
            array (
                'title' => 'subnetwork_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            118 =>
            array (
                'title' => 'subnetwork_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            119 =>
            array (
                'title' => 'subnetwork_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            120 =>
            array (
                'title' => 'subnetwork_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            121 =>
            array (
                'title' => 'gateway_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            122 =>
            array (
                'title' => 'gateway_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            123 =>
            array (
                'title' => 'gateway_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            124 =>
            array (
                'title' => 'gateway_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            125 =>
            array (
                'title' => 'gateway_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            126 =>
            array (
                'title' => 'external_connected_entity_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            127 =>
            array (
                'title' => 'external_connected_entity_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            128 =>
            array (
                'title' => 'external_connected_entity_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            129 =>
            array (
                'title' => 'external_connected_entity_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            130 =>
            array (
                'title' => 'external_connected_entity_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            131 =>
            array (
                'title' => 'network_switch_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            132 =>
            array (
                'title' => 'network_switch_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            133 =>
            array (
                'title' => 'network_switch_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            134 =>
            array (
                'title' => 'network_switch_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            135 =>
            array (
                'title' => 'network_switch_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            136 =>
            array (
                'title' => 'router_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            137 =>
            array (
                'title' => 'router_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            138 =>
            array (
                'title' => 'router_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            139 =>
            array (
                'title' => 'router_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            140 =>
            array (
                'title' => 'router_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            141 =>
            array (
                'title' => 'security_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            142 =>
            array (
                'title' => 'security_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            143 =>
            array (
                'title' => 'security_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            144 =>
            array (
                'title' => 'security_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            145 =>
            array (
                'title' => 'security_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            146 =>
            array (
                'title' => 'dhcp_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            147 =>
            array (
                'title' => 'dhcp_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            148 =>
            array (
                'title' => 'dhcp_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            149 =>
            array (
                'title' => 'dhcp_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            150 =>
            array (
                'title' => 'dhcp_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            151 =>
            array (
                'title' => 'dnsserver_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            152 =>
            array (
                'title' => 'dnsserver_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            153 =>
            array (
                'title' => 'dnsserver_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            154 =>
            array (
                'title' => 'dnsserver_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            155 =>
            array (
                'title' => 'dnsserver_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            156 =>
            array (
                'title' => 'logical_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            157 =>
            array (
                'title' => 'logical_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            158 =>
            array (
                'title' => 'logical_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            159 =>
            array (
                'title' => 'logical_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            160 =>
            array (
                'title' => 'logical_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            161 =>
            array (
                'title' => 'physicalinfrastructure_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            162 =>
            array (
                'title' => 'site_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            163 =>
            array (
                'title' => 'site_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            164 =>
            array (
                'title' => 'site_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            165 =>
            array (
                'title' => 'site_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            166 =>
            array (
                'title' => 'site_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            167 =>
            array (
                'title' => 'building_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            168 =>
            array (
                'title' => 'building_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            169 =>
            array (
                'title' => 'building_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            170 =>
            array (
                'title' => 'building_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            171 =>
            array (
                'title' => 'building_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            172 =>
            array (
                'title' => 'bay_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            173 =>
            array (
                'title' => 'bay_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            174 =>
            array (
                'title' => 'bay_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 =>
            array (
                'title' => 'bay_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 =>
            array (
                'title' => 'bay_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 =>
            array (
                'title' => 'physical_server_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 =>
            array (
                'title' => 'physical_server_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 =>
            array (
                'title' => 'physical_server_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 =>
            array (
                'title' => 'physical_server_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 =>
            array (
                'title' => 'physical_server_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 =>
            array (
                'title' => 'workstation_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 =>
            array (
                'title' => 'workstation_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 =>
            array (
                'title' => 'workstation_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 =>
            array (
                'title' => 'workstation_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 =>
            array (
                'title' => 'workstation_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 =>
            array (
                'title' => 'storage_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 =>
            array (
                'title' => 'storage_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 =>
            array (
                'title' => 'storage_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 =>
            array (
                'title' => 'storage_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 =>
            array (
                'title' => 'storage_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            192 =>
            array (
                'title' => 'peripheral_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            193 =>
            array (
                'title' => 'peripheral_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            194 =>
            array (
                'title' => 'peripheral_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            195 =>
            array (
                'title' => 'peripheral_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            196 =>
            array (
                'title' => 'peripheral_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            197 =>
            array (
                'title' => 'phone_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            198 =>
            array (
                'title' => 'phone_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 =>
            array (
                'title' => 'phone_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 =>
            array (
                'title' => 'phone_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 =>
            array (
                'title' => 'phone_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 =>
            array (
                'title' => 'physical_switch_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 =>
            array (
                'title' => 'physical_switch_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 =>
            array (
                'title' => 'physical_switch_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            205 =>
            array (
                'title' => 'physical_switch_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            206 =>
            array (
                'title' => 'physical_switch_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            207 =>
            array (
                'title' => 'physical_router_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            208 =>
            array (
                'title' => 'physical_router_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            209 =>
            array (
                'title' => 'physical_router_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            210 =>
            array (
                'title' => 'physical_router_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 =>
            array (
                'title' => 'physical_router_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 =>
            array (
                'title' => 'wifi_terminal_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 =>
            array (
                'title' => 'wifi_terminal_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 =>
            array (
                'title' => 'wifi_terminal_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            215 =>
            array (
                'title' => 'wifi_terminal_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            216 =>
            array (
                'title' => 'wifi_terminal_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 =>
            array (
                'title' => 'physical_security_device_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 =>
            array (
                'title' => 'physical_security_device_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 =>
            array (
                'title' => 'physical_security_device_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 =>
            array (
                'title' => 'physical_security_device_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 =>
            array (
                'title' => 'physical_security_device_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 =>
            array (
                'title' => 'wan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 =>
            array (
                'title' => 'wan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 =>
            array (
                'title' => 'wan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            225 =>
            array (
                'title' => 'wan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            226 =>
            array (
                'title' => 'wan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            227 =>
            array (
                'title' => 'man_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            228 =>
            array (
                'title' => 'man_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            229 =>
            array (
                'title' => 'man_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            230 =>
            array (
                'title' => 'man_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            231 =>
            array (
                'title' => 'man_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            232 =>
            array (
                'title' => 'lan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            233 =>
            array (
                'title' => 'lan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            234 =>
            array (
                'title' => 'lan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            235 =>
            array (
                'title' => 'lan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            236 =>
            array (
                'title' => 'lan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            237 =>
            array (
                'title' => 'vlan_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 =>
            array (
                'title' => 'vlan_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            239 =>
            array (
                'title' => 'vlan_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            240 =>
            array (
                'title' => 'vlan_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            241 =>
            array (
                'title' => 'vlan_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            242 =>
            array (
                'title' => 'application_module_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            243 =>
            array (
                'title' => 'application_module_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            244 =>
            array (
                'title' => 'application_module_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            245 =>
            array (
                'title' => 'application_module_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            246 =>
            array (
                'title' => 'application_module_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            247 =>
            array (
                'title' => 'audit_log_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            248 =>
            array (
                'title' => 'audit_log_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            249 =>
            array (
                'title' => 'macro_processus_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            250 =>
            array (
                'title' => 'macro_processus_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            251 =>
            array (
                'title' => 'macro_processus_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            252 =>
            array (
                'title' => 'macro_processus_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            253 =>
            array (
                'title' => 'macro_processus_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            254 =>
            array (
                'title' => 'configuration_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            255 =>
            array (
                'title' => 'profile_password_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            256 =>
            array (
                'title' => 'certificate_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            257 =>
            array (
                'title' => 'certificate_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            258 =>
            array (
                'title' => 'certificate_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            259 =>
            array (
                'title' => 'certificate_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            260 =>
            array (
                'title' => 'certificate_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            261 =>
            array (
                'title' => 'configure',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            262 =>
            array (
                'title' => 'physical_link_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            263 =>
            array (
                'title' => 'physical_link_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            264 =>
            array (
                'title' => 'physical_link_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            265 =>
            array (
                'title' => 'physical_link_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            266 =>
            array (
                'title' => 'physical_link_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            267 =>
            array (
                'title' => 'gdpr_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            268 =>
            array (
                'title' => 'security_control_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            269 =>
            array (
                'title' => 'security_control_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            270 =>
            array (
                'title' => 'security_control_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            271 =>
            array (
                'title' => 'security_control_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            272 =>
            array (
                'title' => 'security_control_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            273 =>
            array (
                'title' => 'data_processing_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            274 =>
            array (
                'title' => 'data_processing_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            275 =>
            array (
                'title' => 'data_processing_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            276 =>
            array (
                'title' => 'data_processing_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            277 =>
            array (
                'title' => 'data_processing_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            278 =>
            array (
                'title' => 'patching_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            279 =>
            array (
                'title' => 'patching_make',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            280 =>
            array (
                'title' => 'cluster_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            281 =>
            array (
                'title' => 'cluster_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            282 =>
            array (
                'title' => 'cluster_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            283 =>
            array (
                'title' => 'cluster_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            284 =>
            array (
                'title' => 'cluster_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            285 =>
            array (
                'title' => 'logical_flow_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            286 =>
            array (
                'title' => 'logical_flow_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            287 =>
            array (
                'title' => 'logical_flow_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            288 =>
            array (
                'title' => 'logical_flow_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 =>
            array (
                'title' => 'logical_flow_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            290 =>
            array (
                'title' => 'admin_user_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            291 =>
            array (
                'title' => 'admin_user_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            292 =>
            array (
                'title' => 'admin_user_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            293 =>
            array (
                'title' => 'admin_user_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            294 =>
            array (
                'title' => 'admin_user_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            295 =>
            array (
                'title' => 'graph_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            296 =>
            array (
                'title' => 'graph_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            297 =>
            array (
                'title' => 'graph_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            298 =>
            array (
                'title' => 'graph_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            299 =>
            array (
                'title' => 'graph_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            300 =>
            array (
                'title' => 'container_create',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            301 =>
            array (
                'title' => 'container_edit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            302 =>
            array (
                'title' => 'container_show',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            303 =>
            array (
                'title' => 'container_delete',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            304 =>
            array (
                'title' => 'container_access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));


    }
}

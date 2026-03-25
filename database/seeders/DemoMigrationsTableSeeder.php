<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMigrationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('migrations')->delete();

        \DB::table('migrations')->insert([
            0 => [
                'id' => 1,
                'migration' => '2016_06_01_000001_create_oauth_auth_codes_table',
                'batch' => 1,
            ],
            1 => [
                'id' => 2,
                'migration' => '2016_06_01_000002_create_oauth_access_tokens_table',
                'batch' => 1,
            ],
            2 => [
                'id' => 3,
                'migration' => '2016_06_01_000003_create_oauth_refresh_tokens_table',
                'batch' => 1,
            ],
            3 => [
                'id' => 4,
                'migration' => '2016_06_01_000004_create_oauth_clients_table',
                'batch' => 1,
            ],
            4 => [
                'id' => 5,
                'migration' => '2016_06_01_000005_create_oauth_personal_access_clients_table',
                'batch' => 1,
            ],
            5 => [
                'id' => 6,
                'migration' => '2021_05_08_191249_create_activities_table',
                'batch' => 1,
            ],
            6 => [
                'id' => 7,
                'migration' => '2021_05_08_191249_create_activity_operation_table',
                'batch' => 1,
            ],
            7 => [
                'id' => 8,
                'migration' => '2021_05_08_191249_create_activity_process_table',
                'batch' => 1,
            ],
            8 => [
                'id' => 9,
                'migration' => '2021_05_08_191249_create_actor_operation_table',
                'batch' => 1,
            ],
            9 => [
                'id' => 10,
                'migration' => '2021_05_08_191249_create_actors_table',
                'batch' => 1,
            ],
            10 => [
                'id' => 11,
                'migration' => '2021_05_08_191249_create_annuaires_table',
                'batch' => 1,
            ],
            11 => [
                'id' => 12,
                'migration' => '2021_05_08_191249_create_application_blocks_table',
                'batch' => 1,
            ],
            12 => [
                'id' => 13,
                'migration' => '2021_05_08_191249_create_application_module_application_service_table',
                'batch' => 1,
            ],
            13 => [
                'id' => 14,
                'migration' => '2021_05_08_191249_create_application_modules_table',
                'batch' => 1,
            ],
            14 => [
                'id' => 15,
                'migration' => '2021_05_08_191249_create_application_service_m_application_table',
                'batch' => 1,
            ],
            15 => [
                'id' => 16,
                'migration' => '2021_05_08_191249_create_application_services_table',
                'batch' => 1,
            ],
            16 => [
                'id' => 17,
                'migration' => '2021_05_08_191249_create_audit_logs_table',
                'batch' => 1,
            ],
            17 => [
                'id' => 18,
                'migration' => '2021_05_08_191249_create_bay_wifi_terminal_table',
                'batch' => 1,
            ],
            18 => [
                'id' => 19,
                'migration' => '2021_05_08_191249_create_bays_table',
                'batch' => 1,
            ],
            19 => [
                'id' => 20,
                'migration' => '2021_05_08_191249_create_buildings_table',
                'batch' => 1,
            ],
            20 => [
                'id' => 21,
                'migration' => '2021_05_08_191249_create_database_entity_table',
                'batch' => 1,
            ],
            21 => [
                'id' => 22,
                'migration' => '2021_05_08_191249_create_database_information_table',
                'batch' => 1,
            ],
            22 => [
                'id' => 23,
                'migration' => '2021_05_08_191249_create_database_m_application_table',
                'batch' => 1,
            ],
            23 => [
                'id' => 24,
                'migration' => '2021_05_08_191249_create_databases_table',
                'batch' => 1,
            ],
            24 => [
                'id' => 25,
                'migration' => '2021_05_08_191249_create_dhcp_servers_table',
                'batch' => 1,
            ],
            25 => [
                'id' => 26,
                'migration' => '2021_05_08_191249_create_dnsservers_table',
                'batch' => 1,
            ],
            26 => [
                'id' => 27,
                'migration' => '2021_05_08_191249_create_domaine_ad_forest_ad_table',
                'batch' => 1,
            ],
            27 => [
                'id' => 28,
                'migration' => '2021_05_08_191249_create_domaine_ads_table',
                'batch' => 1,
            ],
            28 => [
                'id' => 29,
                'migration' => '2021_05_08_191249_create_entities_table',
                'batch' => 1,
            ],
            29 => [
                'id' => 30,
                'migration' => '2021_05_08_191249_create_entity_m_application_table',
                'batch' => 1,
            ],
            30 => [
                'id' => 31,
                'migration' => '2021_05_08_191249_create_entity_process_table',
                'batch' => 1,
            ],
            31 => [
                'id' => 32,
                'migration' => '2021_05_08_191249_create_external_connected_entities_table',
                'batch' => 1,
            ],
            32 => [
                'id' => 33,
                'migration' => '2021_05_08_191249_create_external_connected_entity_network_table',
                'batch' => 1,
            ],
            33 => [
                'id' => 34,
                'migration' => '2021_05_08_191249_create_fluxes_table',
                'batch' => 1,
            ],
            34 => [
                'id' => 35,
                'migration' => '2021_05_08_191249_create_forest_ads_table',
                'batch' => 1,
            ],
            35 => [
                'id' => 36,
                'migration' => '2021_05_08_191249_create_gateways_table',
                'batch' => 1,
            ],
            36 => [
                'id' => 37,
                'migration' => '2021_05_08_191249_create_information_process_table',
                'batch' => 1,
            ],
            37 => [
                'id' => 38,
                'migration' => '2021_05_08_191249_create_information_table',
                'batch' => 1,
            ],
            38 => [
                'id' => 39,
                'migration' => '2021_05_08_191249_create_lan_man_table',
                'batch' => 1,
            ],
            39 => [
                'id' => 40,
                'migration' => '2021_05_08_191249_create_lan_wan_table',
                'batch' => 1,
            ],
            40 => [
                'id' => 41,
                'migration' => '2021_05_08_191249_create_lans_table',
                'batch' => 1,
            ],
            41 => [
                'id' => 42,
                'migration' => '2021_05_08_191249_create_logical_server_m_application_table',
                'batch' => 1,
            ],
            42 => [
                'id' => 43,
                'migration' => '2021_05_08_191249_create_logical_server_physical_server_table',
                'batch' => 1,
            ],
            43 => [
                'id' => 44,
                'migration' => '2021_05_08_191249_create_logical_servers_table',
                'batch' => 1,
            ],
            44 => [
                'id' => 45,
                'migration' => '2021_05_08_191249_create_m_application_process_table',
                'batch' => 1,
            ],
            45 => [
                'id' => 46,
                'migration' => '2021_05_08_191249_create_m_applications_table',
                'batch' => 1,
            ],
            46 => [
                'id' => 47,
                'migration' => '2021_05_08_191249_create_macro_processuses_table',
                'batch' => 1,
            ],
            47 => [
                'id' => 48,
                'migration' => '2021_05_08_191249_create_man_wan_table',
                'batch' => 1,
            ],
            48 => [
                'id' => 49,
                'migration' => '2021_05_08_191249_create_mans_table',
                'batch' => 1,
            ],
            49 => [
                'id' => 50,
                'migration' => '2021_05_08_191249_create_media_table',
                'batch' => 1,
            ],
            50 => [
                'id' => 51,
                'migration' => '2021_05_08_191249_create_network_subnetword_table',
                'batch' => 1,
            ],
            51 => [
                'id' => 52,
                'migration' => '2021_05_08_191249_create_network_switches_table',
                'batch' => 1,
            ],
            52 => [
                'id' => 53,
                'migration' => '2021_05_08_191249_create_networks_table',
                'batch' => 1,
            ],
            53 => [
                'id' => 54,
                'migration' => '2021_05_08_191249_create_operation_task_table',
                'batch' => 1,
            ],
            54 => [
                'id' => 55,
                'migration' => '2021_05_08_191249_create_operations_table',
                'batch' => 1,
            ],
            55 => [
                'id' => 56,
                'migration' => '2021_05_08_191249_create_password_resets_table',
                'batch' => 1,
            ],
            56 => [
                'id' => 57,
                'migration' => '2021_05_08_191249_create_peripherals_table',
                'batch' => 1,
            ],
            57 => [
                'id' => 58,
                'migration' => '2021_05_08_191249_create_permission_role_table',
                'batch' => 1,
            ],
            58 => [
                'id' => 59,
                'migration' => '2021_05_08_191249_create_permissions_table',
                'batch' => 1,
            ],
            59 => [
                'id' => 60,
                'migration' => '2021_05_08_191249_create_phones_table',
                'batch' => 1,
            ],
            60 => [
                'id' => 61,
                'migration' => '2021_05_08_191249_create_physical_router_vlan_table',
                'batch' => 1,
            ],
            61 => [
                'id' => 62,
                'migration' => '2021_05_08_191249_create_physical_routers_table',
                'batch' => 1,
            ],
            62 => [
                'id' => 63,
                'migration' => '2021_05_08_191249_create_physical_security_devices_table',
                'batch' => 1,
            ],
            63 => [
                'id' => 64,
                'migration' => '2021_05_08_191249_create_physical_servers_table',
                'batch' => 1,
            ],
            64 => [
                'id' => 65,
                'migration' => '2021_05_08_191249_create_physical_switches_table',
                'batch' => 1,
            ],
            65 => [
                'id' => 66,
                'migration' => '2021_05_08_191249_create_processes_table',
                'batch' => 1,
            ],
            66 => [
                'id' => 67,
                'migration' => '2021_05_08_191249_create_relations_table',
                'batch' => 1,
            ],
            67 => [
                'id' => 68,
                'migration' => '2021_05_08_191249_create_role_user_table',
                'batch' => 1,
            ],
            68 => [
                'id' => 69,
                'migration' => '2021_05_08_191249_create_roles_table',
                'batch' => 1,
            ],
            69 => [
                'id' => 70,
                'migration' => '2021_05_08_191249_create_routers_table',
                'batch' => 1,
            ],
            70 => [
                'id' => 71,
                'migration' => '2021_05_08_191249_create_security_devices_table',
                'batch' => 1,
            ],
            71 => [
                'id' => 72,
                'migration' => '2021_05_08_191249_create_sites_table',
                'batch' => 1,
            ],
            72 => [
                'id' => 73,
                'migration' => '2021_05_08_191249_create_storage_devices_table',
                'batch' => 1,
            ],
            73 => [
                'id' => 74,
                'migration' => '2021_05_08_191249_create_subnetworks_table',
                'batch' => 1,
            ],
            74 => [
                'id' => 75,
                'migration' => '2021_05_08_191249_create_tasks_table',
                'batch' => 1,
            ],
            75 => [
                'id' => 76,
                'migration' => '2021_05_08_191249_create_users_table',
                'batch' => 1,
            ],
            76 => [
                'id' => 77,
                'migration' => '2021_05_08_191249_create_vlans_table',
                'batch' => 1,
            ],
            77 => [
                'id' => 78,
                'migration' => '2021_05_08_191249_create_wans_table',
                'batch' => 1,
            ],
            78 => [
                'id' => 79,
                'migration' => '2021_05_08_191249_create_wifi_terminals_table',
                'batch' => 1,
            ],
            79 => [
                'id' => 80,
                'migration' => '2021_05_08_191249_create_workstations_table',
                'batch' => 1,
            ],
            80 => [
                'id' => 81,
                'migration' => '2021_05_08_191249_create_zone_admins_table',
                'batch' => 1,
            ],
            81 => [
                'id' => 82,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_activity_operation_table',
                'batch' => 1,
            ],
            82 => [
                'id' => 83,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_activity_process_table',
                'batch' => 1,
            ],
            83 => [
                'id' => 84,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_actor_operation_table',
                'batch' => 1,
            ],
            84 => [
                'id' => 85,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_annuaires_table',
                'batch' => 1,
            ],
            85 => [
                'id' => 86,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_application_module_application_service_table',
                'batch' => 1,
            ],
            86 => [
                'id' => 87,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_application_service_m_application_table',
                'batch' => 1,
            ],
            87 => [
                'id' => 88,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_bay_wifi_terminal_table',
                'batch' => 1,
            ],
            88 => [
                'id' => 89,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_bays_table',
                'batch' => 1,
            ],
            89 => [
                'id' => 90,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_buildings_table',
                'batch' => 1,
            ],
            90 => [
                'id' => 91,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_database_entity_table',
                'batch' => 1,
            ],
            91 => [
                'id' => 92,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_database_information_table',
                'batch' => 1,
            ],
            92 => [
                'id' => 93,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_database_m_application_table',
                'batch' => 1,
            ],
            93 => [
                'id' => 94,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_databases_table',
                'batch' => 1,
            ],
            94 => [
                'id' => 95,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_domaine_ad_forest_ad_table',
                'batch' => 1,
            ],
            95 => [
                'id' => 96,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_entity_m_application_table',
                'batch' => 1,
            ],
            96 => [
                'id' => 97,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_entity_process_table',
                'batch' => 1,
            ],
            97 => [
                'id' => 98,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_external_connected_entity_network_table',
                'batch' => 1,
            ],
            98 => [
                'id' => 99,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_fluxes_table',
                'batch' => 1,
            ],
            99 => [
                'id' => 100,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_forest_ads_table',
                'batch' => 1,
            ],
            100 => [
                'id' => 101,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_information_process_table',
                'batch' => 1,
            ],
            101 => [
                'id' => 102,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_lan_man_table',
                'batch' => 1,
            ],
            102 => [
                'id' => 103,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_lan_wan_table',
                'batch' => 1,
            ],
            103 => [
                'id' => 104,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_logical_server_m_application_table',
                'batch' => 1,
            ],
            104 => [
                'id' => 105,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_logical_server_physical_server_table',
                'batch' => 1,
            ],
            105 => [
                'id' => 106,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_m_application_process_table',
                'batch' => 1,
            ],
            106 => [
                'id' => 107,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_m_applications_table',
                'batch' => 1,
            ],
            107 => [
                'id' => 108,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_man_wan_table',
                'batch' => 1,
            ],
            108 => [
                'id' => 109,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_network_subnetword_table',
                'batch' => 1,
            ],
            109 => [
                'id' => 110,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_operation_task_table',
                'batch' => 1,
            ],
            110 => [
                'id' => 111,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_peripherals_table',
                'batch' => 1,
            ],
            111 => [
                'id' => 112,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_permission_role_table',
                'batch' => 1,
            ],
            112 => [
                'id' => 113,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_phones_table',
                'batch' => 1,
            ],
            113 => [
                'id' => 114,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_physical_router_vlan_table',
                'batch' => 1,
            ],
            114 => [
                'id' => 115,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_physical_routers_table',
                'batch' => 1,
            ],
            115 => [
                'id' => 116,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_physical_security_devices_table',
                'batch' => 1,
            ],
            116 => [
                'id' => 117,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_physical_servers_table',
                'batch' => 1,
            ],
            117 => [
                'id' => 118,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_physical_switches_table',
                'batch' => 1,
            ],
            118 => [
                'id' => 119,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_processes_table',
                'batch' => 1,
            ],
            119 => [
                'id' => 120,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_relations_table',
                'batch' => 1,
            ],
            120 => [
                'id' => 121,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_role_user_table',
                'batch' => 1,
            ],
            121 => [
                'id' => 122,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_storage_devices_table',
                'batch' => 1,
            ],
            122 => [
                'id' => 123,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_subnetworks_table',
                'batch' => 1,
            ],
            123 => [
                'id' => 124,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_wifi_terminals_table',
                'batch' => 1,
            ],
            124 => [
                'id' => 125,
                'migration' => '2021_05_08_191251_add_foreign_keys_to_workstations_table',
                'batch' => 1,
            ],
            125 => [
                'id' => 126,
                'migration' => '2021_05_13_180642_add_cidt_criteria',
                'batch' => 1,
            ],
            126 => [
                'id' => 127,
                'migration' => '2021_05_19_161123_rename_subnetwork',
                'batch' => 1,
            ],
            127 => [
                'id' => 128,
                'migration' => '2021_06_22_170555_add_type',
                'batch' => 1,
            ],
            128 => [
                'id' => 129,
                'migration' => '2021_07_14_071311_create_certificates_table',
                'batch' => 1,
            ],
            129 => [
                'id' => 130,
                'migration' => '2021_08_08_125856_config_right',
                'batch' => 1,
            ],
            130 => [
                'id' => 131,
                'migration' => '2021_08_11_201624_certificate_application_link',
                'batch' => 1,
            ],
            131 => [
                'id' => 132,
                'migration' => '2021_08_18_171048_network_redesign',
                'batch' => 1,
            ],
            132 => [
                'id' => 133,
                'migration' => '2021_08_20_034757_default_gateway',
                'batch' => 1,
            ],
            133 => [
                'id' => 134,
                'migration' => '2021_08_28_152910_cleanup',
                'batch' => 1,
            ],
            134 => [
                'id' => 135,
                'migration' => '2021_09_19_125048_relation-inportance',
                'batch' => 1,
            ],
            135 => [
                'id' => 136,
                'migration' => '2021_09_21_161028_add_router_ip',
                'batch' => 1,
            ],
            136 => [
                'id' => 137,
                'migration' => '2021_09_22_114706_add_security_ciat',
                'batch' => 1,
            ],
            137 => [
                'id' => 138,
                'migration' => '2021_09_23_192127_rename_descrition',
                'batch' => 1,
            ],
            138 => [
                'id' => 139,
                'migration' => '2021_09_28_205405_add_direction_to_flows',
                'batch' => 1,
            ],
            139 => [
                'id' => 140,
                'migration' => '2021_10_12_210233_physical_router_name_type',
                'batch' => 1,
            ],
            140 => [
                'id' => 141,
                'migration' => '2021_10_19_102610_add_address_ip',
                'batch' => 1,
            ],
            141 => [
                'id' => 142,
                'migration' => '2021_11_23_204551_add_app_version',
                'batch' => 1,
            ],
            142 => [
                'id' => 143,
                'migration' => '2022_02_08_210603_create_cartographer_m_application_table',
                'batch' => 1,
            ],
            143 => [
                'id' => 144,
                'migration' => '2022_02_22_32654_add_cert_status',
                'batch' => 1,
            ],
            144 => [
                'id' => 145,
                'migration' => '2022_02_27_162738_add_functional_referent_to_m_application',
                'batch' => 1,
            ],
            145 => [
                'id' => 146,
                'migration' => '2022_02_27_163129_add_editor_to_m_application',
                'batch' => 1,
            ],
            146 => [
                'id' => 147,
                'migration' => '2022_02_27_192155_add_date_fields_to_m_application',
                'batch' => 1,
            ],
            147 => [
                'id' => 148,
                'migration' => '2022_02_28_205630_create_m_application_event_table',
                'batch' => 1,
            ],
            148 => [
                'id' => 149,
                'migration' => '2022_05_02_123756_add_update_to_logical_servers',
                'batch' => 1,
            ],
            149 => [
                'id' => 150,
                'migration' => '2022_05_18_140331_add_is_external_column_to_entities',
                'batch' => 1,
            ],
            150 => [
                'id' => 151,
                'migration' => '2022_05_21_103208_add_type_property_to_entities',
                'batch' => 1,
            ],
            151 => [
                'id' => 152,
                'migration' => '2022_06_27_061444_application_workstation',
                'batch' => 1,
            ],
            152 => [
                'id' => 153,
                'migration' => '2022_07_28_105153_add_link_operation_process',
                'batch' => 1,
            ],
            153 => [
                'id' => 154,
                'migration' => '2022_08_11_165441_add_vpn_fields',
                'batch' => 1,
            ],
            154 => [
                'id' => 155,
                'migration' => '2022_09_13_204845_cert_last_notification',
                'batch' => 1,
            ],
            155 => [
                'id' => 156,
                'migration' => '2022_12_17_115624_rto_rpo',
                'batch' => 1,
            ],
            156 => [
                'id' => 157,
                'migration' => '2023_01_03_205224_database_logical_server',
                'batch' => 1,
            ],
            157 => [
                'id' => 158,
                'migration' => '2023_01_08_123726_add_physical_link',
                'batch' => 1,
            ],
            158 => [
                'id' => 159,
                'migration' => '2023_01_27_165009_add_flux_nature',
                'batch' => 1,
            ],
            159 => [
                'id' => 160,
                'migration' => '2023_01_28_145242_add_logical_devices_link',
                'batch' => 1,
            ],
            160 => [
                'id' => 161,
                'migration' => '2023_02_09_164940_gdpr',
                'batch' => 1,
            ],
            161 => [
                'id' => 162,
                'migration' => '2023_03_16_123031_create_documents_table',
                'batch' => 1,
            ],
            162 => [
                'id' => 163,
                'migration' => '2023_03_22_185812_create_cpe',
                'batch' => 1,
            ],
        ]);

    }
}

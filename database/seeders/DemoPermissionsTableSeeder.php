<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPermissionsTableSeeder extends Seeder
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
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'permission_create',
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'permission_edit',
            ),
            3 =>
            array (
                'id' => 4,
                'title' => 'permission_show',
            ),
            4 =>
            array (
                'id' => 5,
                'title' => 'permission_delete',
            ),
            5 =>
            array (
                'id' => 6,
                'title' => 'permission_access',
            ),
            6 =>
            array (
                'id' => 7,
                'title' => 'role_create',
            ),
            7 =>
            array (
                'id' => 8,
                'title' => 'role_edit',
            ),
            8 =>
            array (
                'id' => 9,
                'title' => 'role_show',
            ),
            9 =>
            array (
                'id' => 10,
                'title' => 'role_delete',
            ),
            10 =>
            array (
                'id' => 11,
                'title' => 'role_access',
            ),
            11 =>
            array (
                'id' => 12,
                'title' => 'user_create',
            ),
            12 =>
            array (
                'id' => 13,
                'title' => 'user_edit',
            ),
            13 =>
            array (
                'id' => 14,
                'title' => 'user_show',
            ),
            14 =>
            array (
                'id' => 15,
                'title' => 'user_delete',
            ),
            15 =>
            array (
                'id' => 16,
                'title' => 'user_access',
            ),
            16 =>
            array (
                'id' => 17,
                'title' => 'entity_create',
            ),
            17 =>
            array (
                'id' => 18,
                'title' => 'entity_edit',
            ),
            18 =>
            array (
                'id' => 19,
                'title' => 'entity_show',
            ),
            19 =>
            array (
                'id' => 20,
                'title' => 'entity_delete',
            ),
            20 =>
            array (
                'id' => 21,
                'title' => 'entity_access',
            ),
            21 =>
            array (
                'id' => 22,
                'title' => 'ecosystem_access',
            ),
            22 =>
            array (
                'id' => 23,
                'title' => 'relation_create',
            ),
            23 =>
            array (
                'id' => 24,
                'title' => 'relation_edit',
            ),
            24 =>
            array (
                'id' => 25,
                'title' => 'relation_show',
            ),
            25 =>
            array (
                'id' => 26,
                'title' => 'relation_delete',
            ),
            26 =>
            array (
                'id' => 27,
                'title' => 'relation_access',
            ),
            27 =>
            array (
                'id' => 28,
                'title' => 'process_create',
            ),
            28 =>
            array (
                'id' => 29,
                'title' => 'process_edit',
            ),
            29 =>
            array (
                'id' => 30,
                'title' => 'process_show',
            ),
            30 =>
            array (
                'id' => 31,
                'title' => 'process_delete',
            ),
            31 =>
            array (
                'id' => 32,
                'title' => 'process_access',
            ),
            32 =>
            array (
                'id' => 33,
                'title' => 'metier_access',
            ),
            33 =>
            array (
                'id' => 34,
                'title' => 'operation_create',
            ),
            34 =>
            array (
                'id' => 35,
                'title' => 'operation_edit',
            ),
            35 =>
            array (
                'id' => 36,
                'title' => 'operation_show',
            ),
            36 =>
            array (
                'id' => 37,
                'title' => 'operation_delete',
            ),
            37 =>
            array (
                'id' => 38,
                'title' => 'operation_access',
            ),
            38 =>
            array (
                'id' => 39,
                'title' => 'actor_create',
            ),
            39 =>
            array (
                'id' => 40,
                'title' => 'actor_edit',
            ),
            40 =>
            array (
                'id' => 41,
                'title' => 'actor_show',
            ),
            41 =>
            array (
                'id' => 42,
                'title' => 'actor_delete',
            ),
            42 =>
            array (
                'id' => 43,
                'title' => 'actor_access',
            ),
            43 =>
            array (
                'id' => 44,
                'title' => 'activity_create',
            ),
            44 =>
            array (
                'id' => 45,
                'title' => 'activity_edit',
            ),
            45 =>
            array (
                'id' => 46,
                'title' => 'activity_show',
            ),
            46 =>
            array (
                'id' => 47,
                'title' => 'activity_delete',
            ),
            47 =>
            array (
                'id' => 48,
                'title' => 'activity_access',
            ),
            48 =>
            array (
                'id' => 49,
                'title' => 'task_create',
            ),
            49 =>
            array (
                'id' => 50,
                'title' => 'task_edit',
            ),
            50 =>
            array (
                'id' => 51,
                'title' => 'task_show',
            ),
            51 =>
            array (
                'id' => 52,
                'title' => 'task_delete',
            ),
            52 =>
            array (
                'id' => 53,
                'title' => 'task_access',
            ),
            53 =>
            array (
                'id' => 54,
                'title' => 'information_create',
            ),
            54 =>
            array (
                'id' => 55,
                'title' => 'information_edit',
            ),
            55 =>
            array (
                'id' => 56,
                'title' => 'information_show',
            ),
            56 =>
            array (
                'id' => 57,
                'title' => 'information_delete',
            ),
            57 =>
            array (
                'id' => 58,
                'title' => 'information_access',
            ),
            58 =>
            array (
                'id' => 59,
                'title' => 'application_block_create',
            ),
            59 =>
            array (
                'id' => 60,
                'title' => 'application_block_edit',
            ),
            60 =>
            array (
                'id' => 61,
                'title' => 'application_block_show',
            ),
            61 =>
            array (
                'id' => 62,
                'title' => 'application_block_delete',
            ),
            62 =>
            array (
                'id' => 63,
                'title' => 'application_block_access',
            ),
            63 =>
            array (
                'id' => 64,
                'title' => 'application_create',
            ),
            64 =>
            array (
                'id' => 65,
                'title' => 'application_edit',
            ),
            65 =>
            array (
                'id' => 66,
                'title' => 'application_show',
            ),
            66 =>
            array (
                'id' => 67,
                'title' => 'application_delete',
            ),
            67 =>
            array (
                'id' => 68,
                'title' => 'application_access',
            ),
            68 =>
            array (
                'id' => 69,
                'title' => 'papplication_access',
            ),
            69 =>
            array (
                'id' => 70,
                'title' => 'm_application_create',
            ),
            70 =>
            array (
                'id' => 71,
                'title' => 'm_application_edit',
            ),
            71 =>
            array (
                'id' => 72,
                'title' => 'm_application_show',
            ),
            72 =>
            array (
                'id' => 73,
                'title' => 'm_application_delete',
            ),
            73 =>
            array (
                'id' => 74,
                'title' => 'm_application_access',
            ),
            74 =>
            array (
                'id' => 75,
                'title' => 'application_service_create',
            ),
            75 =>
            array (
                'id' => 76,
                'title' => 'application_service_edit',
            ),
            76 =>
            array (
                'id' => 77,
                'title' => 'application_service_show',
            ),
            77 =>
            array (
                'id' => 78,
                'title' => 'application_service_delete',
            ),
            78 =>
            array (
                'id' => 79,
                'title' => 'application_service_access',
            ),
            79 =>
            array (
                'id' => 80,
                'title' => 'database_create',
            ),
            80 =>
            array (
                'id' => 81,
                'title' => 'database_edit',
            ),
            81 =>
            array (
                'id' => 82,
                'title' => 'database_show',
            ),
            82 =>
            array (
                'id' => 83,
                'title' => 'database_delete',
            ),
            83 =>
            array (
                'id' => 84,
                'title' => 'database_access',
            ),
            84 =>
            array (
                'id' => 85,
                'title' => 'flux_create',
            ),
            85 =>
            array (
                'id' => 86,
                'title' => 'flux_edit',
            ),
            86 =>
            array (
                'id' => 87,
                'title' => 'flux_show',
            ),
            87 =>
            array (
                'id' => 88,
                'title' => 'flux_delete',
            ),
            88 =>
            array (
                'id' => 89,
                'title' => 'flux_access',
            ),
            89 =>
            array (
                'id' => 90,
                'title' => 'zone_admin_create',
            ),
            90 =>
            array (
                'id' => 91,
                'title' => 'zone_admin_edit',
            ),
            91 =>
            array (
                'id' => 92,
                'title' => 'zone_admin_show',
            ),
            92 =>
            array (
                'id' => 93,
                'title' => 'zone_admin_delete',
            ),
            93 =>
            array (
                'id' => 94,
                'title' => 'zone_admin_access',
            ),
            94 =>
            array (
                'id' => 95,
                'title' => 'administration_access',
            ),
            95 =>
            array (
                'id' => 96,
                'title' => 'annuaire_create',
            ),
            96 =>
            array (
                'id' => 97,
                'title' => 'annuaire_edit',
            ),
            97 =>
            array (
                'id' => 98,
                'title' => 'annuaire_show',
            ),
            98 =>
            array (
                'id' => 99,
                'title' => 'annuaire_delete',
            ),
            99 =>
            array (
                'id' => 100,
                'title' => 'annuaire_access',
            ),
            100 =>
            array (
                'id' => 101,
                'title' => 'forest_ad_create',
            ),
            101 =>
            array (
                'id' => 102,
                'title' => 'forest_ad_edit',
            ),
            102 =>
            array (
                'id' => 103,
                'title' => 'forest_ad_show',
            ),
            103 =>
            array (
                'id' => 104,
                'title' => 'forest_ad_delete',
            ),
            104 =>
            array (
                'id' => 105,
                'title' => 'forest_ad_access',
            ),
            105 =>
            array (
                'id' => 106,
                'title' => 'domaine_ad_create',
            ),
            106 =>
            array (
                'id' => 107,
                'title' => 'domaine_ad_edit',
            ),
            107 =>
            array (
                'id' => 108,
                'title' => 'domaine_ad_show',
            ),
            108 =>
            array (
                'id' => 109,
                'title' => 'domaine_ad_delete',
            ),
            109 =>
            array (
                'id' => 110,
                'title' => 'domaine_ad_access',
            ),
            110 =>
            array (
                'id' => 111,
                'title' => 'infrastructure_access',
            ),
            111 =>
            array (
                'id' => 112,
                'title' => 'network_create',
            ),
            112 =>
            array (
                'id' => 113,
                'title' => 'network_edit',
            ),
            113 =>
            array (
                'id' => 114,
                'title' => 'network_show',
            ),
            114 =>
            array (
                'id' => 115,
                'title' => 'network_delete',
            ),
            115 =>
            array (
                'id' => 116,
                'title' => 'network_access',
            ),
            116 =>
            array (
                'id' => 117,
                'title' => 'subnetwork_create',
            ),
            117 =>
            array (
                'id' => 118,
                'title' => 'subnetwork_edit',
            ),
            118 =>
            array (
                'id' => 119,
                'title' => 'subnetwork_show',
            ),
            119 =>
            array (
                'id' => 120,
                'title' => 'subnetwork_delete',
            ),
            120 =>
            array (
                'id' => 121,
                'title' => 'subnetwork_access',
            ),
            121 =>
            array (
                'id' => 122,
                'title' => 'gateway_create',
            ),
            122 =>
            array (
                'id' => 123,
                'title' => 'gateway_edit',
            ),
            123 =>
            array (
                'id' => 124,
                'title' => 'gateway_show',
            ),
            124 =>
            array (
                'id' => 125,
                'title' => 'gateway_delete',
            ),
            125 =>
            array (
                'id' => 126,
                'title' => 'gateway_access',
            ),
            126 =>
            array (
                'id' => 127,
                'title' => 'external_connected_entity_create',
            ),
            127 =>
            array (
                'id' => 128,
                'title' => 'external_connected_entity_edit',
            ),
            128 =>
            array (
                'id' => 129,
                'title' => 'external_connected_entity_show',
            ),
            129 =>
            array (
                'id' => 130,
                'title' => 'external_connected_entity_delete',
            ),
            130 =>
            array (
                'id' => 131,
                'title' => 'external_connected_entity_access',
            ),
            131 =>
            array (
                'id' => 132,
                'title' => 'network_switch_create',
            ),
            132 =>
            array (
                'id' => 133,
                'title' => 'network_switch_edit',
            ),
            133 =>
            array (
                'id' => 134,
                'title' => 'network_switch_show',
            ),
            134 =>
            array (
                'id' => 135,
                'title' => 'network_switch_delete',
            ),
            135 =>
            array (
                'id' => 136,
                'title' => 'network_switch_access',
            ),
            136 =>
            array (
                'id' => 137,
                'title' => 'router_create',
            ),
            137 =>
            array (
                'id' => 138,
                'title' => 'router_edit',
            ),
            138 =>
            array (
                'id' => 139,
                'title' => 'router_show',
            ),
            139 =>
            array (
                'id' => 140,
                'title' => 'router_delete',
            ),
            140 =>
            array (
                'id' => 141,
                'title' => 'router_access',
            ),
            141 =>
            array (
                'id' => 142,
                'title' => 'security_device_create',
            ),
            142 =>
            array (
                'id' => 143,
                'title' => 'security_device_edit',
            ),
            143 =>
            array (
                'id' => 144,
                'title' => 'security_device_show',
            ),
            144 =>
            array (
                'id' => 145,
                'title' => 'security_device_delete',
            ),
            145 =>
            array (
                'id' => 146,
                'title' => 'security_device_access',
            ),
            146 =>
            array (
                'id' => 147,
                'title' => 'dhcp_server_create',
            ),
            147 =>
            array (
                'id' => 148,
                'title' => 'dhcp_server_edit',
            ),
            148 =>
            array (
                'id' => 149,
                'title' => 'dhcp_server_show',
            ),
            149 =>
            array (
                'id' => 150,
                'title' => 'dhcp_server_delete',
            ),
            150 =>
            array (
                'id' => 151,
                'title' => 'dhcp_server_access',
            ),
            151 =>
            array (
                'id' => 152,
                'title' => 'dnsserver_create',
            ),
            152 =>
            array (
                'id' => 153,
                'title' => 'dnsserver_edit',
            ),
            153 =>
            array (
                'id' => 154,
                'title' => 'dnsserver_show',
            ),
            154 =>
            array (
                'id' => 155,
                'title' => 'dnsserver_delete',
            ),
            155 =>
            array (
                'id' => 156,
                'title' => 'dnsserver_access',
            ),
            156 =>
            array (
                'id' => 157,
                'title' => 'logical_server_create',
            ),
            157 =>
            array (
                'id' => 158,
                'title' => 'logical_server_edit',
            ),
            158 =>
            array (
                'id' => 159,
                'title' => 'logical_server_show',
            ),
            159 =>
            array (
                'id' => 160,
                'title' => 'logical_server_delete',
            ),
            160 =>
            array (
                'id' => 161,
                'title' => 'logical_server_access',
            ),
            161 =>
            array (
                'id' => 162,
                'title' => 'physicalinfrastructure_access',
            ),
            162 =>
            array (
                'id' => 163,
                'title' => 'site_create',
            ),
            163 =>
            array (
                'id' => 164,
                'title' => 'site_edit',
            ),
            164 =>
            array (
                'id' => 165,
                'title' => 'site_show',
            ),
            165 =>
            array (
                'id' => 166,
                'title' => 'site_delete',
            ),
            166 =>
            array (
                'id' => 167,
                'title' => 'site_access',
            ),
            167 =>
            array (
                'id' => 168,
                'title' => 'building_create',
            ),
            168 =>
            array (
                'id' => 169,
                'title' => 'building_edit',
            ),
            169 =>
            array (
                'id' => 170,
                'title' => 'building_show',
            ),
            170 =>
            array (
                'id' => 171,
                'title' => 'building_delete',
            ),
            171 =>
            array (
                'id' => 172,
                'title' => 'building_access',
            ),
            172 =>
            array (
                'id' => 173,
                'title' => 'bay_create',
            ),
            173 =>
            array (
                'id' => 174,
                'title' => 'bay_edit',
            ),
            174 =>
            array (
                'id' => 175,
                'title' => 'bay_show',
            ),
            175 =>
            array (
                'id' => 176,
                'title' => 'bay_delete',
            ),
            176 =>
            array (
                'id' => 177,
                'title' => 'bay_access',
            ),
            177 =>
            array (
                'id' => 178,
                'title' => 'physical_server_create',
            ),
            178 =>
            array (
                'id' => 179,
                'title' => 'physical_server_edit',
            ),
            179 =>
            array (
                'id' => 180,
                'title' => 'physical_server_show',
            ),
            180 =>
            array (
                'id' => 181,
                'title' => 'physical_server_delete',
            ),
            181 =>
            array (
                'id' => 182,
                'title' => 'physical_server_access',
            ),
            182 =>
            array (
                'id' => 183,
                'title' => 'workstation_create',
            ),
            183 =>
            array (
                'id' => 184,
                'title' => 'workstation_edit',
            ),
            184 =>
            array (
                'id' => 185,
                'title' => 'workstation_show',
            ),
            185 =>
            array (
                'id' => 186,
                'title' => 'workstation_delete',
            ),
            186 =>
            array (
                'id' => 187,
                'title' => 'workstation_access',
            ),
            187 =>
            array (
                'id' => 188,
                'title' => 'storage_device_create',
            ),
            188 =>
            array (
                'id' => 189,
                'title' => 'storage_device_edit',
            ),
            189 =>
            array (
                'id' => 190,
                'title' => 'storage_device_show',
            ),
            190 =>
            array (
                'id' => 191,
                'title' => 'storage_device_delete',
            ),
            191 =>
            array (
                'id' => 192,
                'title' => 'storage_device_access',
            ),
            192 =>
            array (
                'id' => 193,
                'title' => 'peripheral_create',
            ),
            193 =>
            array (
                'id' => 194,
                'title' => 'peripheral_edit',
            ),
            194 =>
            array (
                'id' => 195,
                'title' => 'peripheral_show',
            ),
            195 =>
            array (
                'id' => 196,
                'title' => 'peripheral_delete',
            ),
            196 =>
            array (
                'id' => 197,
                'title' => 'peripheral_access',
            ),
            197 =>
            array (
                'id' => 198,
                'title' => 'phone_create',
            ),
            198 =>
            array (
                'id' => 199,
                'title' => 'phone_edit',
            ),
            199 =>
            array (
                'id' => 200,
                'title' => 'phone_show',
            ),
            200 =>
            array (
                'id' => 201,
                'title' => 'phone_delete',
            ),
            201 =>
            array (
                'id' => 202,
                'title' => 'phone_access',
            ),
            202 =>
            array (
                'id' => 203,
                'title' => 'physical_switch_create',
            ),
            203 =>
            array (
                'id' => 204,
                'title' => 'physical_switch_edit',
            ),
            204 =>
            array (
                'id' => 205,
                'title' => 'physical_switch_show',
            ),
            205 =>
            array (
                'id' => 206,
                'title' => 'physical_switch_delete',
            ),
            206 =>
            array (
                'id' => 207,
                'title' => 'physical_switch_access',
            ),
            207 =>
            array (
                'id' => 208,
                'title' => 'physical_router_create',
            ),
            208 =>
            array (
                'id' => 209,
                'title' => 'physical_router_edit',
            ),
            209 =>
            array (
                'id' => 210,
                'title' => 'physical_router_show',
            ),
            210 =>
            array (
                'id' => 211,
                'title' => 'physical_router_delete',
            ),
            211 =>
            array (
                'id' => 212,
                'title' => 'physical_router_access',
            ),
            212 =>
            array (
                'id' => 213,
                'title' => 'wifi_terminal_create',
            ),
            213 =>
            array (
                'id' => 214,
                'title' => 'wifi_terminal_edit',
            ),
            214 =>
            array (
                'id' => 215,
                'title' => 'wifi_terminal_show',
            ),
            215 =>
            array (
                'id' => 216,
                'title' => 'wifi_terminal_delete',
            ),
            216 =>
            array (
                'id' => 217,
                'title' => 'wifi_terminal_access',
            ),
            217 =>
            array (
                'id' => 218,
                'title' => 'physical_security_device_create',
            ),
            218 =>
            array (
                'id' => 219,
                'title' => 'physical_security_device_edit',
            ),
            219 =>
            array (
                'id' => 220,
                'title' => 'physical_security_device_show',
            ),
            220 =>
            array (
                'id' => 221,
                'title' => 'physical_security_device_delete',
            ),
            221 =>
            array (
                'id' => 222,
                'title' => 'physical_security_device_access',
            ),
            222 =>
            array (
                'id' => 223,
                'title' => 'wan_create',
            ),
            223 =>
            array (
                'id' => 224,
                'title' => 'wan_edit',
            ),
            224 =>
            array (
                'id' => 225,
                'title' => 'wan_show',
            ),
            225 =>
            array (
                'id' => 226,
                'title' => 'wan_delete',
            ),
            226 =>
            array (
                'id' => 227,
                'title' => 'wan_access',
            ),
            227 =>
            array (
                'id' => 228,
                'title' => 'man_create',
            ),
            228 =>
            array (
                'id' => 229,
                'title' => 'man_edit',
            ),
            229 =>
            array (
                'id' => 230,
                'title' => 'man_show',
            ),
            230 =>
            array (
                'id' => 231,
                'title' => 'man_delete',
            ),
            231 =>
            array (
                'id' => 232,
                'title' => 'man_access',
            ),
            232 =>
            array (
                'id' => 233,
                'title' => 'lan_create',
            ),
            233 =>
            array (
                'id' => 234,
                'title' => 'lan_edit',
            ),
            234 =>
            array (
                'id' => 235,
                'title' => 'lan_show',
            ),
            235 =>
            array (
                'id' => 236,
                'title' => 'lan_delete',
            ),
            236 =>
            array (
                'id' => 237,
                'title' => 'lan_access',
            ),
            237 =>
            array (
                'id' => 238,
                'title' => 'vlan_create',
            ),
            238 =>
            array (
                'id' => 239,
                'title' => 'vlan_edit',
            ),
            239 =>
            array (
                'id' => 240,
                'title' => 'vlan_show',
            ),
            240 =>
            array (
                'id' => 241,
                'title' => 'vlan_delete',
            ),
            241 =>
            array (
                'id' => 242,
                'title' => 'vlan_access',
            ),
            242 =>
            array (
                'id' => 243,
                'title' => 'application_module_create',
            ),
            243 =>
            array (
                'id' => 244,
                'title' => 'application_module_edit',
            ),
            244 =>
            array (
                'id' => 245,
                'title' => 'application_module_show',
            ),
            245 =>
            array (
                'id' => 246,
                'title' => 'application_module_delete',
            ),
            246 =>
            array (
                'id' => 247,
                'title' => 'application_module_access',
            ),
            247 =>
            array (
                'id' => 248,
                'title' => 'audit_log_show',
            ),
            248 =>
            array (
                'id' => 249,
                'title' => 'audit_log_access',
            ),
            249 =>
            array (
                'id' => 250,
                'title' => 'macro_processus_create',
            ),
            250 =>
            array (
                'id' => 251,
                'title' => 'macro_processus_edit',
            ),
            251 =>
            array (
                'id' => 252,
                'title' => 'macro_processus_show',
            ),
            252 =>
            array (
                'id' => 253,
                'title' => 'macro_processus_delete',
            ),
            253 =>
            array (
                'id' => 254,
                'title' => 'macro_processus_access',
            ),
            254 =>
            array (
                'id' => 255,
                'title' => 'configuration_access',
            ),
            255 =>
            array (
                'id' => 256,
                'title' => 'profile_password_edit',
            ),
            256 =>
            array (
                'id' => 257,
                'title' => 'certificate_create',
            ),
            257 =>
            array (
                'id' => 258,
                'title' => 'certificate_edit',
            ),
            258 =>
            array (
                'id' => 259,
                'title' => 'certificate_show',
            ),
            259 =>
            array (
                'id' => 260,
                'title' => 'certificate_delete',
            ),
            260 =>
            array (
                'id' => 261,
                'title' => 'certificate_access',
            ),
            261 =>
            array (
                'id' => 262,
                'title' => 'configure',
            ),
            262 =>
            array (
                'id' => 263,
                'title' => 'physical_link_create',
            ),
            263 =>
            array (
                'id' => 264,
                'title' => 'physical_link_edit',
            ),
            264 =>
            array (
                'id' => 265,
                'title' => 'physical_link_show',
            ),
            265 =>
            array (
                'id' => 266,
                'title' => 'physical_link_delete',
            ),
            266 =>
            array (
                'id' => 267,
                'title' => 'physical_link_access',
            ),
            267 =>
            array (
                'id' => 268,
                'title' => 'gdpr_access',
            ),
            268 =>
            array (
                'id' => 269,
                'title' => 'security_controls_create',
            ),
            269 =>
            array (
                'id' => 270,
                'title' => 'security_controls_edit',
            ),
            270 =>
            array (
                'id' => 271,
                'title' => 'security_controls_show',
            ),
            271 =>
            array (
                'id' => 272,
                'title' => 'security_controls_delete',
            ),
            272 =>
            array (
                'id' => 273,
                'title' => 'security_controls_access',
            ),
            273 =>
            array (
                'id' => 274,
                'title' => 'data_processing_register_create',
            ),
            274 =>
            array (
                'id' => 275,
                'title' => 'data_processing_register_edit',
            ),
            275 =>
            array (
                'id' => 276,
                'title' => 'data_processing_register_show',
            ),
            276 =>
            array (
                'id' => 277,
                'title' => 'data_processing_register_delete',
            ),
            277 =>
            array (
                'id' => 278,
                'title' => 'data_processing_register_access',
            ),
            278 =>
            array (
                'id' => 279,
                'title' => 'patching_show',
            ),
            279 =>
            array (
                'id' => 280,
                'title' => 'patching_make',
            ),
            280 =>
            array (
                'id' => 281,
                'title' => 'cluster_create',
            ),
            281 =>
            array (
                'id' => 282,
                'title' => 'cluster_edit',
            ),
            282 =>
            array (
                'id' => 283,
                'title' => 'cluster_show',
            ),
            283 =>
            array (
                'id' => 284,
                'title' => 'cluster_delete',
            ),
            284 =>
            array (
                'id' => 285,
                'title' => 'cluster_access',
            ),
        ));
    }
}

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

        \DB::table('permissions')->insert([
            0 => [
                'id' => 1,
                'title' => 'user_management_access',
            ],
            1 => [
                'id' => 2,
                'title' => 'permission_create',
            ],
            2 => [
                'id' => 3,
                'title' => 'permission_edit',
            ],
            3 => [
                'id' => 4,
                'title' => 'permission_show',
            ],
            4 => [
                'id' => 5,
                'title' => 'permission_delete',
            ],
            5 => [
                'id' => 6,
                'title' => 'permission_access',
            ],
            6 => [
                'id' => 7,
                'title' => 'role_create',
            ],
            7 => [
                'id' => 8,
                'title' => 'role_edit',
            ],
            8 => [
                'id' => 9,
                'title' => 'role_show',
            ],
            9 => [
                'id' => 10,
                'title' => 'role_delete',
            ],
            10 => [
                'id' => 11,
                'title' => 'role_access',
            ],
            11 => [
                'id' => 12,
                'title' => 'user_create',
            ],
            12 => [
                'id' => 13,
                'title' => 'user_edit',
            ],
            13 => [
                'id' => 14,
                'title' => 'user_show',
            ],
            14 => [
                'id' => 15,
                'title' => 'user_delete',
            ],
            15 => [
                'id' => 16,
                'title' => 'user_access',
            ],
            16 => [
                'id' => 17,
                'title' => 'entity_create',
            ],
            17 => [
                'id' => 18,
                'title' => 'entity_edit',
            ],
            18 => [
                'id' => 19,
                'title' => 'entity_show',
            ],
            19 => [
                'id' => 20,
                'title' => 'entity_delete',
            ],
            20 => [
                'id' => 21,
                'title' => 'entity_access',
            ],
            21 => [
                'id' => 22,
                'title' => 'ecosystem_access',
            ],
            22 => [
                'id' => 23,
                'title' => 'relation_create',
            ],
            23 => [
                'id' => 24,
                'title' => 'relation_edit',
            ],
            24 => [
                'id' => 25,
                'title' => 'relation_show',
            ],
            25 => [
                'id' => 26,
                'title' => 'relation_delete',
            ],
            26 => [
                'id' => 27,
                'title' => 'relation_access',
            ],
            27 => [
                'id' => 28,
                'title' => 'process_create',
            ],
            28 => [
                'id' => 29,
                'title' => 'process_edit',
            ],
            29 => [
                'id' => 30,
                'title' => 'process_show',
            ],
            30 => [
                'id' => 31,
                'title' => 'process_delete',
            ],
            31 => [
                'id' => 32,
                'title' => 'process_access',
            ],
            32 => [
                'id' => 33,
                'title' => 'metier_access',
            ],
            33 => [
                'id' => 34,
                'title' => 'operation_create',
            ],
            34 => [
                'id' => 35,
                'title' => 'operation_edit',
            ],
            35 => [
                'id' => 36,
                'title' => 'operation_show',
            ],
            36 => [
                'id' => 37,
                'title' => 'operation_delete',
            ],
            37 => [
                'id' => 38,
                'title' => 'operation_access',
            ],
            38 => [
                'id' => 39,
                'title' => 'actor_create',
            ],
            39 => [
                'id' => 40,
                'title' => 'actor_edit',
            ],
            40 => [
                'id' => 41,
                'title' => 'actor_show',
            ],
            41 => [
                'id' => 42,
                'title' => 'actor_delete',
            ],
            42 => [
                'id' => 43,
                'title' => 'actor_access',
            ],
            43 => [
                'id' => 44,
                'title' => 'activity_create',
            ],
            44 => [
                'id' => 45,
                'title' => 'activity_edit',
            ],
            45 => [
                'id' => 46,
                'title' => 'activity_show',
            ],
            46 => [
                'id' => 47,
                'title' => 'activity_delete',
            ],
            47 => [
                'id' => 48,
                'title' => 'activity_access',
            ],
            48 => [
                'id' => 49,
                'title' => 'task_create',
            ],
            49 => [
                'id' => 50,
                'title' => 'task_edit',
            ],
            50 => [
                'id' => 51,
                'title' => 'task_show',
            ],
            51 => [
                'id' => 52,
                'title' => 'task_delete',
            ],
            52 => [
                'id' => 53,
                'title' => 'task_access',
            ],
            53 => [
                'id' => 54,
                'title' => 'information_create',
            ],
            54 => [
                'id' => 55,
                'title' => 'information_edit',
            ],
            55 => [
                'id' => 56,
                'title' => 'information_show',
            ],
            56 => [
                'id' => 57,
                'title' => 'information_delete',
            ],
            57 => [
                'id' => 58,
                'title' => 'information_access',
            ],
            58 => [
                'id' => 59,
                'title' => 'application_block_create',
            ],
            59 => [
                'id' => 60,
                'title' => 'application_block_edit',
            ],
            60 => [
                'id' => 61,
                'title' => 'application_block_show',
            ],
            61 => [
                'id' => 62,
                'title' => 'application_block_delete',
            ],
            62 => [
                'id' => 63,
                'title' => 'application_block_access',
            ],
            63 => [
                'id' => 64,
                'title' => 'application_create',
            ],
            64 => [
                'id' => 65,
                'title' => 'application_edit',
            ],
            65 => [
                'id' => 66,
                'title' => 'application_show',
            ],
            66 => [
                'id' => 67,
                'title' => 'application_delete',
            ],
            67 => [
                'id' => 68,
                'title' => 'application_access',
            ],
            68 => [
                'id' => 69,
                'title' => 'papplication_access',
            ],
            69 => [
                'id' => 70,
                'title' => 'm_application_create',
            ],
            70 => [
                'id' => 71,
                'title' => 'm_application_edit',
            ],
            71 => [
                'id' => 72,
                'title' => 'm_application_show',
            ],
            72 => [
                'id' => 73,
                'title' => 'm_application_delete',
            ],
            73 => [
                'id' => 74,
                'title' => 'm_application_access',
            ],
            74 => [
                'id' => 75,
                'title' => 'application_service_create',
            ],
            75 => [
                'id' => 76,
                'title' => 'application_service_edit',
            ],
            76 => [
                'id' => 77,
                'title' => 'application_service_show',
            ],
            77 => [
                'id' => 78,
                'title' => 'application_service_delete',
            ],
            78 => [
                'id' => 79,
                'title' => 'application_service_access',
            ],
            79 => [
                'id' => 80,
                'title' => 'database_create',
            ],
            80 => [
                'id' => 81,
                'title' => 'database_edit',
            ],
            81 => [
                'id' => 82,
                'title' => 'database_show',
            ],
            82 => [
                'id' => 83,
                'title' => 'database_delete',
            ],
            83 => [
                'id' => 84,
                'title' => 'database_access',
            ],
            84 => [
                'id' => 85,
                'title' => 'flux_create',
            ],
            85 => [
                'id' => 86,
                'title' => 'flux_edit',
            ],
            86 => [
                'id' => 87,
                'title' => 'flux_show',
            ],
            87 => [
                'id' => 88,
                'title' => 'flux_delete',
            ],
            88 => [
                'id' => 89,
                'title' => 'flux_access',
            ],
            89 => [
                'id' => 90,
                'title' => 'zone_admin_create',
            ],
            90 => [
                'id' => 91,
                'title' => 'zone_admin_edit',
            ],
            91 => [
                'id' => 92,
                'title' => 'zone_admin_show',
            ],
            92 => [
                'id' => 93,
                'title' => 'zone_admin_delete',
            ],
            93 => [
                'id' => 94,
                'title' => 'zone_admin_access',
            ],
            94 => [
                'id' => 95,
                'title' => 'administration_access',
            ],
            95 => [
                'id' => 96,
                'title' => 'annuaire_create',
            ],
            96 => [
                'id' => 97,
                'title' => 'annuaire_edit',
            ],
            97 => [
                'id' => 98,
                'title' => 'annuaire_show',
            ],
            98 => [
                'id' => 99,
                'title' => 'annuaire_delete',
            ],
            99 => [
                'id' => 100,
                'title' => 'annuaire_access',
            ],
            100 => [
                'id' => 101,
                'title' => 'forest_ad_create',
            ],
            101 => [
                'id' => 102,
                'title' => 'forest_ad_edit',
            ],
            102 => [
                'id' => 103,
                'title' => 'forest_ad_show',
            ],
            103 => [
                'id' => 104,
                'title' => 'forest_ad_delete',
            ],
            104 => [
                'id' => 105,
                'title' => 'forest_ad_access',
            ],
            105 => [
                'id' => 106,
                'title' => 'domaine_ad_create',
            ],
            106 => [
                'id' => 107,
                'title' => 'domaine_ad_edit',
            ],
            107 => [
                'id' => 108,
                'title' => 'domaine_ad_show',
            ],
            108 => [
                'id' => 109,
                'title' => 'domaine_ad_delete',
            ],
            109 => [
                'id' => 110,
                'title' => 'domaine_ad_access',
            ],
            110 => [
                'id' => 111,
                'title' => 'infrastructure_access',
            ],
            111 => [
                'id' => 112,
                'title' => 'network_create',
            ],
            112 => [
                'id' => 113,
                'title' => 'network_edit',
            ],
            113 => [
                'id' => 114,
                'title' => 'network_show',
            ],
            114 => [
                'id' => 115,
                'title' => 'network_delete',
            ],
            115 => [
                'id' => 116,
                'title' => 'network_access',
            ],
            116 => [
                'id' => 117,
                'title' => 'subnetwork_create',
            ],
            117 => [
                'id' => 118,
                'title' => 'subnetwork_edit',
            ],
            118 => [
                'id' => 119,
                'title' => 'subnetwork_show',
            ],
            119 => [
                'id' => 120,
                'title' => 'subnetwork_delete',
            ],
            120 => [
                'id' => 121,
                'title' => 'subnetwork_access',
            ],
            121 => [
                'id' => 122,
                'title' => 'gateway_create',
            ],
            122 => [
                'id' => 123,
                'title' => 'gateway_edit',
            ],
            123 => [
                'id' => 124,
                'title' => 'gateway_show',
            ],
            124 => [
                'id' => 125,
                'title' => 'gateway_delete',
            ],
            125 => [
                'id' => 126,
                'title' => 'gateway_access',
            ],
            126 => [
                'id' => 127,
                'title' => 'external_connected_entity_create',
            ],
            127 => [
                'id' => 128,
                'title' => 'external_connected_entity_edit',
            ],
            128 => [
                'id' => 129,
                'title' => 'external_connected_entity_show',
            ],
            129 => [
                'id' => 130,
                'title' => 'external_connected_entity_delete',
            ],
            130 => [
                'id' => 131,
                'title' => 'external_connected_entity_access',
            ],
            131 => [
                'id' => 132,
                'title' => 'network_switch_create',
            ],
            132 => [
                'id' => 133,
                'title' => 'network_switch_edit',
            ],
            133 => [
                'id' => 134,
                'title' => 'network_switch_show',
            ],
            134 => [
                'id' => 135,
                'title' => 'network_switch_delete',
            ],
            135 => [
                'id' => 136,
                'title' => 'network_switch_access',
            ],
            136 => [
                'id' => 137,
                'title' => 'router_create',
            ],
            137 => [
                'id' => 138,
                'title' => 'router_edit',
            ],
            138 => [
                'id' => 139,
                'title' => 'router_show',
            ],
            139 => [
                'id' => 140,
                'title' => 'router_delete',
            ],
            140 => [
                'id' => 141,
                'title' => 'router_access',
            ],
            141 => [
                'id' => 142,
                'title' => 'security_device_create',
            ],
            142 => [
                'id' => 143,
                'title' => 'security_device_edit',
            ],
            143 => [
                'id' => 144,
                'title' => 'security_device_show',
            ],
            144 => [
                'id' => 145,
                'title' => 'security_device_delete',
            ],
            145 => [
                'id' => 146,
                'title' => 'security_device_access',
            ],
            146 => [
                'id' => 147,
                'title' => 'dhcp_server_create',
            ],
            147 => [
                'id' => 148,
                'title' => 'dhcp_server_edit',
            ],
            148 => [
                'id' => 149,
                'title' => 'dhcp_server_show',
            ],
            149 => [
                'id' => 150,
                'title' => 'dhcp_server_delete',
            ],
            150 => [
                'id' => 151,
                'title' => 'dhcp_server_access',
            ],
            151 => [
                'id' => 152,
                'title' => 'dnsserver_create',
            ],
            152 => [
                'id' => 153,
                'title' => 'dnsserver_edit',
            ],
            153 => [
                'id' => 154,
                'title' => 'dnsserver_show',
            ],
            154 => [
                'id' => 155,
                'title' => 'dnsserver_delete',
            ],
            155 => [
                'id' => 156,
                'title' => 'dnsserver_access',
            ],
            156 => [
                'id' => 157,
                'title' => 'logical_server_create',
            ],
            157 => [
                'id' => 158,
                'title' => 'logical_server_edit',
            ],
            158 => [
                'id' => 159,
                'title' => 'logical_server_show',
            ],
            159 => [
                'id' => 160,
                'title' => 'logical_server_delete',
            ],
            160 => [
                'id' => 161,
                'title' => 'logical_server_access',
            ],
            161 => [
                'id' => 162,
                'title' => 'physicalinfrastructure_access',
            ],
            162 => [
                'id' => 163,
                'title' => 'site_create',
            ],
            163 => [
                'id' => 164,
                'title' => 'site_edit',
            ],
            164 => [
                'id' => 165,
                'title' => 'site_show',
            ],
            165 => [
                'id' => 166,
                'title' => 'site_delete',
            ],
            166 => [
                'id' => 167,
                'title' => 'site_access',
            ],
            167 => [
                'id' => 168,
                'title' => 'building_create',
            ],
            168 => [
                'id' => 169,
                'title' => 'building_edit',
            ],
            169 => [
                'id' => 170,
                'title' => 'building_show',
            ],
            170 => [
                'id' => 171,
                'title' => 'building_delete',
            ],
            171 => [
                'id' => 172,
                'title' => 'building_access',
            ],
            172 => [
                'id' => 173,
                'title' => 'bay_create',
            ],
            173 => [
                'id' => 174,
                'title' => 'bay_edit',
            ],
            174 => [
                'id' => 175,
                'title' => 'bay_show',
            ],
            175 => [
                'id' => 176,
                'title' => 'bay_delete',
            ],
            176 => [
                'id' => 177,
                'title' => 'bay_access',
            ],
            177 => [
                'id' => 178,
                'title' => 'physical_server_create',
            ],
            178 => [
                'id' => 179,
                'title' => 'physical_server_edit',
            ],
            179 => [
                'id' => 180,
                'title' => 'physical_server_show',
            ],
            180 => [
                'id' => 181,
                'title' => 'physical_server_delete',
            ],
            181 => [
                'id' => 182,
                'title' => 'physical_server_access',
            ],
            182 => [
                'id' => 183,
                'title' => 'workstation_create',
            ],
            183 => [
                'id' => 184,
                'title' => 'workstation_edit',
            ],
            184 => [
                'id' => 185,
                'title' => 'workstation_show',
            ],
            185 => [
                'id' => 186,
                'title' => 'workstation_delete',
            ],
            186 => [
                'id' => 187,
                'title' => 'workstation_access',
            ],
            187 => [
                'id' => 188,
                'title' => 'storage_device_create',
            ],
            188 => [
                'id' => 189,
                'title' => 'storage_device_edit',
            ],
            189 => [
                'id' => 190,
                'title' => 'storage_device_show',
            ],
            190 => [
                'id' => 191,
                'title' => 'storage_device_delete',
            ],
            191 => [
                'id' => 192,
                'title' => 'storage_device_access',
            ],
            192 => [
                'id' => 193,
                'title' => 'peripheral_create',
            ],
            193 => [
                'id' => 194,
                'title' => 'peripheral_edit',
            ],
            194 => [
                'id' => 195,
                'title' => 'peripheral_show',
            ],
            195 => [
                'id' => 196,
                'title' => 'peripheral_delete',
            ],
            196 => [
                'id' => 197,
                'title' => 'peripheral_access',
            ],
            197 => [
                'id' => 198,
                'title' => 'phone_create',
            ],
            198 => [
                'id' => 199,
                'title' => 'phone_edit',
            ],
            199 => [
                'id' => 200,
                'title' => 'phone_show',
            ],
            200 => [
                'id' => 201,
                'title' => 'phone_delete',
            ],
            201 => [
                'id' => 202,
                'title' => 'phone_access',
            ],
            202 => [
                'id' => 203,
                'title' => 'physical_switch_create',
            ],
            203 => [
                'id' => 204,
                'title' => 'physical_switch_edit',
            ],
            204 => [
                'id' => 205,
                'title' => 'physical_switch_show',
            ],
            205 => [
                'id' => 206,
                'title' => 'physical_switch_delete',
            ],
            206 => [
                'id' => 207,
                'title' => 'physical_switch_access',
            ],
            207 => [
                'id' => 208,
                'title' => 'physical_router_create',
            ],
            208 => [
                'id' => 209,
                'title' => 'physical_router_edit',
            ],
            209 => [
                'id' => 210,
                'title' => 'physical_router_show',
            ],
            210 => [
                'id' => 211,
                'title' => 'physical_router_delete',
            ],
            211 => [
                'id' => 212,
                'title' => 'physical_router_access',
            ],
            212 => [
                'id' => 213,
                'title' => 'wifi_terminal_create',
            ],
            213 => [
                'id' => 214,
                'title' => 'wifi_terminal_edit',
            ],
            214 => [
                'id' => 215,
                'title' => 'wifi_terminal_show',
            ],
            215 => [
                'id' => 216,
                'title' => 'wifi_terminal_delete',
            ],
            216 => [
                'id' => 217,
                'title' => 'wifi_terminal_access',
            ],
            217 => [
                'id' => 218,
                'title' => 'physical_security_device_create',
            ],
            218 => [
                'id' => 219,
                'title' => 'physical_security_device_edit',
            ],
            219 => [
                'id' => 220,
                'title' => 'physical_security_device_show',
            ],
            220 => [
                'id' => 221,
                'title' => 'physical_security_device_delete',
            ],
            221 => [
                'id' => 222,
                'title' => 'physical_security_device_access',
            ],
            222 => [
                'id' => 223,
                'title' => 'wan_create',
            ],
            223 => [
                'id' => 224,
                'title' => 'wan_edit',
            ],
            224 => [
                'id' => 225,
                'title' => 'wan_show',
            ],
            225 => [
                'id' => 226,
                'title' => 'wan_delete',
            ],
            226 => [
                'id' => 227,
                'title' => 'wan_access',
            ],
            227 => [
                'id' => 228,
                'title' => 'man_create',
            ],
            228 => [
                'id' => 229,
                'title' => 'man_edit',
            ],
            229 => [
                'id' => 230,
                'title' => 'man_show',
            ],
            230 => [
                'id' => 231,
                'title' => 'man_delete',
            ],
            231 => [
                'id' => 232,
                'title' => 'man_access',
            ],
            232 => [
                'id' => 233,
                'title' => 'lan_create',
            ],
            233 => [
                'id' => 234,
                'title' => 'lan_edit',
            ],
            234 => [
                'id' => 235,
                'title' => 'lan_show',
            ],
            235 => [
                'id' => 236,
                'title' => 'lan_delete',
            ],
            236 => [
                'id' => 237,
                'title' => 'lan_access',
            ],
            237 => [
                'id' => 238,
                'title' => 'vlan_create',
            ],
            238 => [
                'id' => 239,
                'title' => 'vlan_edit',
            ],
            239 => [
                'id' => 240,
                'title' => 'vlan_show',
            ],
            240 => [
                'id' => 241,
                'title' => 'vlan_delete',
            ],
            241 => [
                'id' => 242,
                'title' => 'vlan_access',
            ],
            242 => [
                'id' => 243,
                'title' => 'application_module_create',
            ],
            243 => [
                'id' => 244,
                'title' => 'application_module_edit',
            ],
            244 => [
                'id' => 245,
                'title' => 'application_module_show',
            ],
            245 => [
                'id' => 246,
                'title' => 'application_module_delete',
            ],
            246 => [
                'id' => 247,
                'title' => 'application_module_access',
            ],
            247 => [
                'id' => 248,
                'title' => 'audit_log_show',
            ],
            248 => [
                'id' => 249,
                'title' => 'audit_log_access',
            ],
            249 => [
                'id' => 250,
                'title' => 'macro_processus_create',
            ],
            250 => [
                'id' => 251,
                'title' => 'macro_processus_edit',
            ],
            251 => [
                'id' => 252,
                'title' => 'macro_processus_show',
            ],
            252 => [
                'id' => 253,
                'title' => 'macro_processus_delete',
            ],
            253 => [
                'id' => 254,
                'title' => 'macro_processus_access',
            ],
            254 => [
                'id' => 255,
                'title' => 'configuration_access',
            ],
            255 => [
                'id' => 256,
                'title' => 'profile_password_edit',
            ],
            256 => [
                'id' => 257,
                'title' => 'certificate_create',
            ],
            257 => [
                'id' => 258,
                'title' => 'certificate_edit',
            ],
            258 => [
                'id' => 259,
                'title' => 'certificate_show',
            ],
            259 => [
                'id' => 260,
                'title' => 'certificate_delete',
            ],
            260 => [
                'id' => 261,
                'title' => 'certificate_access',
            ],
            261 => [
                'id' => 262,
                'title' => 'configure',
            ],
            262 => [
                'id' => 263,
                'title' => 'physical_link_create',
            ],
            263 => [
                'id' => 264,
                'title' => 'physical_link_edit',
            ],
            264 => [
                'id' => 265,
                'title' => 'physical_link_show',
            ],
            265 => [
                'id' => 266,
                'title' => 'physical_link_delete',
            ],
            266 => [
                'id' => 267,
                'title' => 'physical_link_access',
            ],
            267 => [
                'id' => 268,
                'title' => 'gdpr_access',
            ],
            268 => [
                'id' => 269,
                'title' => 'security_controls_create',
            ],
            269 => [
                'id' => 270,
                'title' => 'security_controls_edit',
            ],
            270 => [
                'id' => 271,
                'title' => 'security_controls_show',
            ],
            271 => [
                'id' => 272,
                'title' => 'security_controls_delete',
            ],
            272 => [
                'id' => 273,
                'title' => 'security_controls_access',
            ],
            273 => [
                'id' => 274,
                'title' => 'data_processing_register_create',
            ],
            274 => [
                'id' => 275,
                'title' => 'data_processing_register_edit',
            ],
            275 => [
                'id' => 276,
                'title' => 'data_processing_register_show',
            ],
            276 => [
                'id' => 277,
                'title' => 'data_processing_register_delete',
            ],
            277 => [
                'id' => 278,
                'title' => 'data_processing_register_access',
            ],
            278 => [
                'id' => 279,
                'title' => 'patching_access',
            ],
            279 => [
                'id' => 280,
                'title' => 'patching_make',
            ],
            280 => [
                'id' => 281,
                'title' => 'cluster_create',
            ],
            281 => [
                'id' => 282,
                'title' => 'cluster_edit',
            ],
            282 => [
                'id' => 283,
                'title' => 'cluster_show',
            ],
            283 => [
                'id' => 284,
                'title' => 'cluster_delete',
            ],
            284 => [
                'id' => 285,
                'title' => 'cluster_access',
            ],
        ]);
    }
}

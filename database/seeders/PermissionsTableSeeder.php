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

        \DB::table('permissions')->insert([
            0 => [
                'title' => 'user_management_access',
            ],
            1 => [
                'title' => 'permission_create',
            ],
            2 => [
                'title' => 'permission_edit',
            ],
            3 => [
                'title' => 'permission_show',
            ],
            4 => [
                'title' => 'permission_delete',
            ],
            5 => [
                'title' => 'permission_access',
            ],
            6 => [
                'title' => 'role_create',
            ],
            7 => [
                'title' => 'role_edit',
            ],
            8 => [
                'title' => 'role_show',
            ],
            9 => [
                'title' => 'role_delete',
            ],
            10 => [
                'title' => 'role_access',
            ],
            11 => [
                'title' => 'user_create',
            ],
            12 => [
                'title' => 'user_edit',
            ],
            13 => [
                'title' => 'user_show',
            ],
            14 => [
                'title' => 'user_delete',
            ],
            15 => [
                'title' => 'user_access',
            ],
            16 => [
                'title' => 'entity_create',
            ],
            17 => [
                'title' => 'entity_edit',
            ],
            18 => [
                'title' => 'entity_show',
            ],
            19 => [
                'title' => 'entity_delete',
            ],
            20 => [
                'title' => 'entity_access',
            ],
            21 => [
                'title' => 'ecosystem_access',
            ],
            22 => [
                'title' => 'relation_create',
            ],
            23 => [
                'title' => 'relation_edit',
            ],
            24 => [
                'title' => 'relation_show',
            ],
            25 => [
                'title' => 'relation_delete',
            ],
            26 => [
                'title' => 'relation_access',
            ],
            27 => [
                'title' => 'process_create',
            ],
            28 => [
                'title' => 'process_edit',
            ],
            29 => [
                'title' => 'process_show',
            ],
            30 => [
                'title' => 'process_delete',
            ],
            31 => [
                'title' => 'process_access',
            ],
            32 => [
                'title' => 'metier_access',
            ],
            33 => [
                'title' => 'operation_create',
            ],
            34 => [
                'title' => 'operation_edit',
            ],
            35 => [
                'title' => 'operation_show',
            ],
            36 => [
                'title' => 'operation_delete',
            ],
            37 => [
                'title' => 'operation_access',
            ],
            38 => [
                'title' => 'actor_create',
            ],
            39 => [
                'title' => 'actor_edit',
            ],
            40 => [
                'title' => 'actor_show',
            ],
            41 => [
                'title' => 'actor_delete',
            ],
            42 => [
                'title' => 'actor_access',
            ],
            43 => [
                'title' => 'activity_create',
            ],
            44 => [
                'title' => 'activity_edit',
            ],
            45 => [
                'title' => 'activity_show',
            ],
            46 => [
                'title' => 'activity_delete',
            ],
            47 => [
                'title' => 'activity_access',
            ],
            48 => [
                'title' => 'task_create',
            ],
            49 => [
                'title' => 'task_edit',
            ],
            50 => [
                'title' => 'task_show',
            ],
            51 => [
                'title' => 'task_delete',
            ],
            52 => [
                'title' => 'task_access',
            ],
            53 => [
                'title' => 'information_create',
            ],
            54 => [
                'title' => 'information_edit',
            ],
            55 => [
                'title' => 'information_show',
            ],
            56 => [
                'title' => 'information_delete',
            ],
            57 => [
                'title' => 'information_access',
            ],
            58 => [
                'title' => 'application_block_create',
            ],
            59 => [
                'title' => 'application_block_edit',
            ],
            60 => [
                'title' => 'application_block_show',
            ],
            61 => [
                'title' => 'application_block_delete',
            ],
            62 => [
                'title' => 'application_block_access',
            ],
            63 => [
                'title' => 'application_create',
            ],
            64 => [
                'title' => 'application_edit',
            ],
            65 => [
                'title' => 'application_show',
            ],
            66 => [
                'title' => 'application_delete',
            ],
            67 => [
                'title' => 'application_access',
            ],
            68 => [
                'title' => 'papplication_access',
            ],
            69 => [
                'title' => 'm_application_create',
            ],
            70 => [
                'title' => 'm_application_edit',
            ],
            71 => [
                'title' => 'm_application_show',
            ],
            72 => [
                'title' => 'm_application_delete',
            ],
            73 => [
                'title' => 'm_application_access',
            ],
            74 => [
                'title' => 'application_service_create',
            ],
            75 => [
                'title' => 'application_service_edit',
            ],
            76 => [
                'title' => 'application_service_show',
            ],
            77 => [
                'title' => 'application_service_delete',
            ],
            78 => [
                'title' => 'application_service_access',
            ],
            79 => [
                'title' => 'database_create',
            ],
            80 => [
                'title' => 'database_edit',
            ],
            81 => [
                'title' => 'database_show',
            ],
            82 => [
                'title' => 'database_delete',
            ],
            83 => [
                'title' => 'database_access',
            ],
            84 => [
                'title' => 'flux_create',
            ],
            85 => [
                'title' => 'flux_edit',
            ],
            86 => [
                'title' => 'flux_show',
            ],
            87 => [
                'title' => 'flux_delete',
            ],
            88 => [
                'title' => 'flux_access',
            ],
            89 => [
                'title' => 'zone_admin_create',
            ],
            90 => [
                'title' => 'zone_admin_edit',
            ],
            91 => [
                'title' => 'zone_admin_show',
            ],
            92 => [
                'title' => 'zone_admin_delete',
            ],
            93 => [
                'title' => 'zone_admin_access',
            ],
            94 => [
                'title' => 'administration_access',
            ],
            95 => [
                'title' => 'annuaire_create',
            ],
            96 => [
                'title' => 'annuaire_edit',
            ],
            97 => [
                'title' => 'annuaire_show',
            ],
            98 => [
                'title' => 'annuaire_delete',
            ],
            99 => [
                'title' => 'annuaire_access',
            ],
            100 => [
                'title' => 'forest_ad_create',
            ],
            101 => [
                'title' => 'forest_ad_edit',
            ],
            102 => [
                'title' => 'forest_ad_show',
            ],
            103 => [
                'title' => 'forest_ad_delete',
            ],
            104 => [
                'title' => 'forest_ad_access',
            ],
            105 => [
                'title' => 'domaine_ad_create',
            ],
            106 => [
                'title' => 'domaine_ad_edit',
            ],
            107 => [
                'title' => 'domaine_ad_show',
            ],
            108 => [
                'title' => 'domaine_ad_delete',
            ],
            109 => [
                'title' => 'domaine_ad_access',
            ],
            110 => [
                'title' => 'infrastructure_access',
            ],
            111 => [
                'title' => 'network_create',
            ],
            112 => [
                'title' => 'network_edit',
            ],
            113 => [
                'title' => 'network_show',
            ],
            114 => [
                'title' => 'network_delete',
            ],
            115 => [
                'title' => 'network_access',
            ],
            116 => [
                'title' => 'subnetwork_create',
            ],
            117 => [
                'title' => 'subnetwork_edit',
            ],
            118 => [
                'title' => 'subnetwork_show',
            ],
            119 => [
                'title' => 'subnetwork_delete',
            ],
            120 => [
                'title' => 'subnetwork_access',
            ],
            121 => [
                'title' => 'gateway_create',
            ],
            122 => [
                'title' => 'gateway_edit',
            ],
            123 => [
                'title' => 'gateway_show',
            ],
            124 => [
                'title' => 'gateway_delete',
            ],
            125 => [
                'title' => 'gateway_access',
            ],
            126 => [
                'title' => 'external_connected_entity_create',
            ],
            127 => [
                'title' => 'external_connected_entity_edit',
            ],
            128 => [
                'title' => 'external_connected_entity_show',
            ],
            129 => [
                'title' => 'external_connected_entity_delete',
            ],
            130 => [
                'title' => 'external_connected_entity_access',
            ],
            131 => [
                'title' => 'network_switch_create',
            ],
            132 => [
                'title' => 'network_switch_edit',
            ],
            133 => [
                'title' => 'network_switch_show',
            ],
            134 => [
                'title' => 'network_switch_delete',
            ],
            135 => [
                'title' => 'network_switch_access',
            ],
            136 => [
                'title' => 'router_create',
            ],
            137 => [
                'title' => 'router_edit',
            ],
            138 => [
                'title' => 'router_show',
            ],
            139 => [
                'title' => 'router_delete',
            ],
            140 => [
                'title' => 'router_access',
            ],
            141 => [
                'title' => 'security_device_create',
            ],
            142 => [
                'title' => 'security_device_edit',
            ],
            143 => [
                'title' => 'security_device_show',
            ],
            144 => [
                'title' => 'security_device_delete',
            ],
            145 => [
                'title' => 'security_device_access',
            ],
            146 => [
                'title' => 'dhcp_server_create',
            ],
            147 => [
                'title' => 'dhcp_server_edit',
            ],
            148 => [
                'title' => 'dhcp_server_show',
            ],
            149 => [
                'title' => 'dhcp_server_delete',
            ],
            150 => [
                'title' => 'dhcp_server_access',
            ],
            151 => [
                'title' => 'dnsserver_create',
            ],
            152 => [
                'title' => 'dnsserver_edit',
            ],
            153 => [
                'title' => 'dnsserver_show',
            ],
            154 => [
                'title' => 'dnsserver_delete',
            ],
            155 => [
                'title' => 'dnsserver_access',
            ],
            156 => [
                'title' => 'logical_server_create',
            ],
            157 => [
                'title' => 'logical_server_edit',
            ],
            158 => [
                'title' => 'logical_server_show',
            ],
            159 => [
                'title' => 'logical_server_delete',
            ],
            160 => [
                'title' => 'logical_server_access',
            ],
            161 => [
                'title' => 'physicalinfrastructure_access',
            ],
            162 => [
                'title' => 'site_create',
            ],
            163 => [
                'title' => 'site_edit',
            ],
            164 => [
                'title' => 'site_show',
            ],
            165 => [
                'title' => 'site_delete',
            ],
            166 => [
                'title' => 'site_access',
            ],
            167 => [
                'title' => 'building_create',
            ],
            168 => [
                'title' => 'building_edit',
            ],
            169 => [
                'title' => 'building_show',
            ],
            170 => [
                'title' => 'building_delete',
            ],
            171 => [
                'title' => 'building_access',
            ],
            172 => [
                'title' => 'bay_create',
            ],
            173 => [
                'title' => 'bay_edit',
            ],
            174 => [
                'title' => 'bay_show',
            ],
            175 => [
                'title' => 'bay_delete',
            ],
            176 => [
                'title' => 'bay_access',
            ],
            177 => [
                'title' => 'physical_server_create',
            ],
            178 => [
                'title' => 'physical_server_edit',
            ],
            179 => [
                'title' => 'physical_server_show',
            ],
            180 => [
                'title' => 'physical_server_delete',
            ],
            181 => [
                'title' => 'physical_server_access',
            ],
            182 => [
                'title' => 'workstation_create',
            ],
            183 => [
                'title' => 'workstation_edit',
            ],
            184 => [
                'title' => 'workstation_show',
            ],
            185 => [
                'title' => 'workstation_delete',
            ],
            186 => [
                'title' => 'workstation_access',
            ],
            187 => [
                'title' => 'storage_device_create',
            ],
            188 => [
                'title' => 'storage_device_edit',
            ],
            189 => [
                'title' => 'storage_device_show',
            ],
            190 => [
                'title' => 'storage_device_delete',
            ],
            191 => [
                'title' => 'storage_device_access',
            ],
            192 => [
                'title' => 'peripheral_create',
            ],
            193 => [
                'title' => 'peripheral_edit',
            ],
            194 => [
                'title' => 'peripheral_show',
            ],
            195 => [
                'title' => 'peripheral_delete',
            ],
            196 => [
                'title' => 'peripheral_access',
            ],
            197 => [
                'title' => 'phone_create',
            ],
            198 => [
                'title' => 'phone_edit',
            ],
            199 => [
                'title' => 'phone_show',
            ],
            200 => [
                'title' => 'phone_delete',
            ],
            201 => [
                'title' => 'phone_access',
            ],
            202 => [
                'title' => 'physical_switch_create',
            ],
            203 => [
                'title' => 'physical_switch_edit',
            ],
            204 => [
                'title' => 'physical_switch_show',
            ],
            205 => [
                'title' => 'physical_switch_delete',
            ],
            206 => [
                'title' => 'physical_switch_access',
            ],
            207 => [
                'title' => 'physical_router_create',
            ],
            208 => [
                'title' => 'physical_router_edit',
            ],
            209 => [
                'title' => 'physical_router_show',
            ],
            210 => [
                'title' => 'physical_router_delete',
            ],
            211 => [
                'title' => 'physical_router_access',
            ],
            212 => [
                'title' => 'wifi_terminal_create',
            ],
            213 => [
                'title' => 'wifi_terminal_edit',
            ],
            214 => [
                'title' => 'wifi_terminal_show',
            ],
            215 => [
                'title' => 'wifi_terminal_delete',
            ],
            216 => [
                'title' => 'wifi_terminal_access',
            ],
            217 => [
                'title' => 'physical_security_device_create',
            ],
            218 => [
                'title' => 'physical_security_device_edit',
            ],
            219 => [
                'title' => 'physical_security_device_show',
            ],
            220 => [
                'title' => 'physical_security_device_delete',
            ],
            221 => [
                'title' => 'physical_security_device_access',
            ],
            222 => [
                'title' => 'wan_create',
            ],
            223 => [
                'title' => 'wan_edit',
            ],
            224 => [
                'title' => 'wan_show',
            ],
            225 => [
                'title' => 'wan_delete',
            ],
            226 => [
                'title' => 'wan_access',
            ],
            227 => [
                'title' => 'man_create',
            ],
            228 => [
                'title' => 'man_edit',
            ],
            229 => [
                'title' => 'man_show',
            ],
            230 => [
                'title' => 'man_delete',
            ],
            231 => [
                'title' => 'man_access',
            ],
            232 => [
                'title' => 'lan_create',
            ],
            233 => [
                'title' => 'lan_edit',
            ],
            234 => [
                'title' => 'lan_show',
            ],
            235 => [
                'title' => 'lan_delete',
            ],
            236 => [
                'title' => 'lan_access',
            ],
            237 => [
                'title' => 'vlan_create',
            ],
            238 => [
                'title' => 'vlan_edit',
            ],
            239 => [
                'title' => 'vlan_show',
            ],
            240 => [
                'title' => 'vlan_delete',
            ],
            241 => [
                'title' => 'vlan_access',
            ],
            242 => [
                'title' => 'application_module_create',
            ],
            243 => [
                'title' => 'application_module_edit',
            ],
            244 => [
                'title' => 'application_module_show',
            ],
            245 => [
                'title' => 'application_module_delete',
            ],
            246 => [
                'title' => 'application_module_access',
            ],
            247 => [
                'title' => 'audit_log_show',
            ],
            248 => [
                'title' => 'audit_log_access',
            ],
            249 => [
                'title' => 'macro_processus_create',
            ],
            250 => [
                'title' => 'macro_processus_edit',
            ],
            251 => [
                'title' => 'macro_processus_show',
            ],
            252 => [
                'title' => 'macro_processus_delete',
            ],
            253 => [
                'title' => 'macro_processus_access',
            ],
            254 => [
                'title' => 'configuration_access',
            ],
            255 => [
                'title' => 'profile_password_edit',
            ],
            256 => [
                'title' => 'certificate_create',
            ],
            257 => [
                'title' => 'certificate_edit',
            ],
            258 => [
                'title' => 'certificate_show',
            ],
            259 => [
                'title' => 'certificate_delete',
            ],
            260 => [
                'title' => 'certificate_access',
            ],
            261 => [
                'title' => 'configure',
            ],
            262 => [
                'title' => 'physical_link_create',
            ],
            263 => [
                'title' => 'physical_link_edit',
            ],
            264 => [
                'title' => 'physical_link_show',
            ],
            265 => [
                'title' => 'physical_link_delete',
            ],
            266 => [
                'title' => 'physical_link_access',
            ],
            267 => [
                'title' => 'gdpr_access',
            ],
            268 => [
                'title' => 'security_control_create',
            ],
            269 => [
                'title' => 'security_control_edit',
            ],
            270 => [
                'title' => 'security_control_show',
            ],
            271 => [
                'title' => 'security_control_delete',
            ],
            272 => [
                'title' => 'security_control_access',
            ],
            273 => [
                'title' => 'data_processing_create',
            ],
            274 => [
                'title' => 'data_processing_edit',
            ],
            275 => [
                'title' => 'data_processing_show',
            ],
            276 => [
                'title' => 'data_processing_delete',
            ],
            277 => [
                'title' => 'data_processing_access',
            ],
            278 => [
                'title' => 'patching_access',
            ],
            279 => [
                'title' => 'patching_make',
            ],
            280 => [
                'title' => 'cluster_create',
            ],
            281 => [
                'title' => 'cluster_edit',
            ],
            282 => [
                'title' => 'cluster_show',
            ],
            283 => [
                'title' => 'cluster_delete',
            ],
            284 => [
                'title' => 'cluster_access',
            ],
            285 => [
                'title' => 'logical_flow_create',
            ],
            286 => [
                'title' => 'logical_flow_edit',
            ],
            287 => [
                'title' => 'logical_flow_show',
            ],
            288 => [
                'title' => 'logical_flow_delete',
            ],
            289 => [
                'title' => 'logical_flow_access',
            ],
            290 => [
                'title' => 'admin_user_create',
            ],
            291 => [
                'title' => 'admin_user_edit',
            ],
            292 => [
                'title' => 'admin_user_show',
            ],
            293 => [
                'title' => 'admin_user_delete',
            ],
            294 => [
                'title' => 'admin_user_access',
            ],
            295 => [
                'title' => 'graph_create',
            ],
            296 => [
                'title' => 'graph_edit',
            ],
            297 => [
                'title' => 'graph_show',
            ],
            298 => [
                'title' => 'graph_delete',
            ],
            299 => [
                'title' => 'graph_access',
            ],
            300 => [
                'title' => 'container_create',
            ],
            301 => [
                'title' => 'container_edit',
            ],
            302 => [
                'title' => 'container_show',
            ],
            303 => [
                'title' => 'container_delete',
            ],
            304 => [
                'title' => 'container_access',
            ],
            305 => [
                'title' => 'tools_access',
            ],
            306 => [
                'title' => 'explore_access',
            ],
            307 => [
                'title' => 'reports_access',
            ],
        ]);

    }
}

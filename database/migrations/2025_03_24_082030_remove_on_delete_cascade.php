<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("activity_document", function (Blueprint $table) {
            $table->dropForeign("activity_id_fk_1472784");
        });
        Schema::table("activity_document", function (Blueprint $table) {
            $table->dropForeign("operation_id_fk_1472794");
        });
        Schema::table("activity_operation", function (Blueprint $table) {
            $table->dropForeign("activity_id_fk_1472704");
        });
        Schema::table("activity_operation", function (Blueprint $table) {
            $table->dropForeign("operation_id_fk_1472704");
        });
        Schema::table("activity_process", function (Blueprint $table) {
            $table->dropForeign("activity_id_fk_1627616");
        });
        Schema::table("activity_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_1627616");
        });
        Schema::table("actor_operation", function (Blueprint $table) {
            $table->dropForeign("actor_id_fk_1472680");
        });
        Schema::table("actor_operation", function (Blueprint $table) {
            $table->dropForeign("operation_id_fk_1472680");
        });
        Schema::table("admin_users", function (Blueprint $table) {
            $table->dropForeign("domain_id_fk_69385935");
        });
        Schema::table("application_module_application_service", function (Blueprint $table) {
            $table->dropForeign("application_module_id_fk_1492414");
        });
        Schema::table("application_module_application_service", function (Blueprint $table) {
            $table->dropForeign("application_service_id_fk_1492414");
        });
        Schema::table("application_service_m_application", function (Blueprint $table) {
            $table->dropForeign("application_service_id_fk_1482585");
        });
        Schema::table("application_service_m_application", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1482585");
        });
        Schema::table("bay_wifi_terminal", function (Blueprint $table) {
            $table->dropForeign("bay_id_fk_1485509");
        });
        Schema::table("bay_wifi_terminal", function (Blueprint $table) {
            $table->dropForeign("wifi_terminal_id_fk_1485509");
        });
        Schema::table("cartographer_m_application", function (Blueprint $table) {
            $table->dropForeign("cartographer_m_application_m_application_id_foreign");
        });
        Schema::table("certificate_logical_server", function (Blueprint $table) {
            $table->dropForeign("certificate_logical_server_certificate_id_foreign");
        });
        Schema::table("certificate_logical_server", function (Blueprint $table) {
            $table->dropForeign("certificate_logical_server_logical_server_id_foreign");
        });
        Schema::table("certificate_m_application", function (Blueprint $table) {
            $table->dropForeign("certificate_m_application_certificate_id_foreign");
        });
        Schema::table("certificate_m_application", function (Blueprint $table) {
            $table->dropForeign("certificate_m_application_m_application_id_foreign");
        });
        Schema::table("data_processing_document", function (Blueprint $table) {
            $table->dropForeign("data_processing_id_fk_42343234");
        });
        Schema::table("data_processing_document", function (Blueprint $table) {
            $table->dropForeign("document_id_fk_3439483");
        });
        Schema::table("data_processing_information", function (Blueprint $table) {
            $table->dropForeign("data_processing_id_fk_493438483");
        });
        Schema::table("data_processing_information", function (Blueprint $table) {
            $table->dropForeign("information_id_fk_0483434");
        });
        Schema::table("data_processing_m_application", function (Blueprint $table) {
            $table->dropForeign("applications_id_fk_0483434");
        });
        Schema::table("data_processing_m_application", function (Blueprint $table) {
            $table->dropForeign("data_processing_id_fk_49838437");
        });
        Schema::table("data_processing_process", function (Blueprint $table) {
            $table->dropForeign("data_processing_id_fk_764545345");
        });
        Schema::table("data_processing_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_0483434");
        });
        Schema::table("database_entity", function (Blueprint $table) {
            $table->dropForeign("database_id_fk_1485563");
        });
        Schema::table("database_entity", function (Blueprint $table) {
            $table->dropForeign("entity_id_fk_1485563");
        });
        Schema::table("database_information", function (Blueprint $table) {
            $table->dropForeign("database_id_fk_1485570");
        });
        Schema::table("database_information", function (Blueprint $table) {
            $table->dropForeign("information_id_fk_1485570");
        });
        Schema::table("database_m_application", function (Blueprint $table) {
            $table->dropForeign("database_id_fk_1482586");
        });
        Schema::table("database_m_application", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1482586");
        });
        Schema::table("document_logical_server", function (Blueprint $table) {
            $table->dropForeign("document_id_fk_1284334");
        });
        Schema::table("document_logical_server", function (Blueprint $table) {
            $table->dropForeign("logical_server_id_fk_43832473");
        });
        Schema::table("document_relation", function (Blueprint $table) {
            $table->dropForeign("document_id_fk_5492844");
        });
        Schema::table("document_relation", function (Blueprint $table) {
            $table->dropForeign("relation_id_fk_6948334");
        });
        Schema::table("domaine_ad_forest_ad", function (Blueprint $table) {
            $table->dropForeign("domaine_ad_id_fk_1492084");
        });
        Schema::table("domaine_ad_forest_ad", function (Blueprint $table) {
            $table->dropForeign("forest_ad_id_fk_1492084");
        });
        Schema::table("entity_document", function (Blueprint $table) {
            $table->dropForeign("document_id_fk_4355430");
        });
        Schema::table("entity_document", function (Blueprint $table) {
            $table->dropForeign("entity_id_fk_4325432");
        });
        Schema::table("entity_m_application", function (Blueprint $table) {
            $table->dropForeign("entity_id_fk_1488611");
        });
        Schema::table("entity_m_application", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1488611");
        });
        Schema::table("entity_process", function (Blueprint $table) {
            $table->dropForeign("entity_id_fk_1627958");
        });
        Schema::table("entity_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_1627958");
        });
        Schema::table("external_connected_entities", function (Blueprint $table) {
            $table->dropForeign("entity_id_fk_1295034");
        });
        Schema::table("external_connected_entities", function (Blueprint $table) {
            $table->dropForeign("network_id_fk_8596554");
        });
        Schema::table("information_process", function (Blueprint $table) {
            $table->dropForeign("information_id_fk_1473025");
        });
        Schema::table("information_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_1473025");
        });
        Schema::table("lan_man", function (Blueprint $table) {
            $table->dropForeign("lan_id_fk_1490345");
        });
        Schema::table("lan_man", function (Blueprint $table) {
            $table->dropForeign("man_id_fk_1490345");
        });
        Schema::table("lan_wan", function (Blueprint $table) {
            $table->dropForeign("lan_id_fk_1490368");
        });
        Schema::table("lan_wan", function (Blueprint $table) {
            $table->dropForeign("wan_id_fk_1490368");
        });
        Schema::table("logical_flows", function (Blueprint $table) {
            $table->dropForeign("router_id_fk_4382393");
        });
        Schema::table("logical_server_m_application", function (Blueprint $table) {
            $table->dropForeign("logical_server_id_fk_1488616");
        });
        Schema::table("logical_server_m_application", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1488616");
        });
        Schema::table("logical_server_physical_server", function (Blueprint $table) {
            $table->dropForeign("logical_server_id_fk_1657961");
        });
        Schema::table("logical_server_physical_server", function (Blueprint $table) {
            $table->dropForeign("physical_server_id_fk_1657961");
        });
        Schema::table("logical_servers", function (Blueprint $table) {
            $table->dropForeign("cluster_id_fk_5435359");
        });
        Schema::table("m_application_events", function (Blueprint $table) {
            $table->dropForeign("m_application_events_m_application_id_foreign");
        });
        Schema::table("m_application_peripheral", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_9878654");
        });
        Schema::table("m_application_peripheral", function (Blueprint $table) {
            $table->dropForeign("peripheral_id_fk_6454564");
        });
        Schema::table("m_application_physical_server", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_5483543");
        });
        Schema::table("m_application_physical_server", function (Blueprint $table) {
            $table->dropForeign("physical_server_id_fk_4543543");
        });
        Schema::table("m_application_process", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1482573");
        });
        Schema::table("m_application_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_1482573");
        });
        Schema::table("m_application_workstation", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_1486547");
        });
        Schema::table("m_application_workstation", function (Blueprint $table) {
            $table->dropForeign("workstation_id_fk_1486547");
        });
        Schema::table("man_wan", function (Blueprint $table) {
            $table->dropForeign("man_id_fk_1490367");
        });
        Schema::table("man_wan", function (Blueprint $table) {
            $table->dropForeign("wan_id_fk_1490367");
        });
        Schema::table("network_switch_physical_switch", function (Blueprint $table) {
            $table->dropForeign("network_switch_id_fk_543323");
        });
        Schema::table("network_switch_physical_switch", function (Blueprint $table) {
            $table->dropForeign("physical_switch_id_fk_4543143");
        });
        Schema::table("operation_task", function (Blueprint $table) {
            $table->dropForeign("operation_id_fk_1472749");
        });
        Schema::table("operation_task", function (Blueprint $table) {
            $table->dropForeign("task_id_fk_1472749");
        });
        Schema::table("operations", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_7945129");
        });
        Schema::table("permission_role", function (Blueprint $table) {
            $table->dropForeign("permission_id_fk_1470794");
        });
        Schema::table("permission_role", function (Blueprint $table) {
            $table->dropForeign("role_id_fk_1470794");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("logical_server_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("logical_server_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("network_switch_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("network_switch_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("peripheral_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("peripheral_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("phone_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("phone_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_router_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_router_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_security_device_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_security_device_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_server_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_server_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_switch_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("physical_switch_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("router_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("router_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("storage_device_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("storage_device_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("wifi_terminal_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("wifi_terminal_src_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("workstation_dest_id_fk");
        });
        Schema::table("physical_links", function (Blueprint $table) {
            $table->dropForeign("workstation_src_id_fk");
        });
        Schema::table("physical_router_router", function (Blueprint $table) {
            $table->dropForeign("physical_router_id_fk_124983");
        });
        Schema::table("physical_router_router", function (Blueprint $table) {
            $table->dropForeign("router_id_fk_958343");
        });
        Schema::table("physical_router_vlan", function (Blueprint $table) {
            $table->dropForeign("physical_router_id_fk_1658250");
        });
        Schema::table("physical_router_vlan", function (Blueprint $table) {
            $table->dropForeign("vlan_id_fk_1658250");
        });
        Schema::table("physical_servers", function (Blueprint $table) {
            $table->dropForeign("cluster_id_fk_5438543");
        });
        Schema::table("relation_values", function (Blueprint $table) {
            $table->dropForeign("relation_id_fk_43243244");
        });
        Schema::table("role_user", function (Blueprint $table) {
            $table->dropForeign("role_id_fk_1470803");
        });
        Schema::table("role_user", function (Blueprint $table) {
            $table->dropForeign("user_id_fk_1470803");
        });
        Schema::table("security_control_m_application", function (Blueprint $table) {
            $table->dropForeign("m_application_id_fk_304958543");
        });
        Schema::table("security_control_m_application", function (Blueprint $table) {
            $table->dropForeign("security_control_id_fk_49294573");
        });
        Schema::table("security_control_process", function (Blueprint $table) {
            $table->dropForeign("process_id_fk_49485754");
        });
        Schema::table("security_control_process", function (Blueprint $table) {
            $table->dropForeign("security_control_id_fk_54354354");
        });

        DB::statement("DELETE FROM `activity_document` WHERE `activity_id` IS NOT NULL AND `activity_id` NOT IN (SELECT `id` FROM `activities`)");
        Schema::table("activity_document", function (Blueprint $table) {
            $table->foreign("activity_id", "activity_document_activity_id_foreign")->references("id")->on("activities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `activity_document` WHERE `document_id` IS NOT NULL AND `document_id` NOT IN (SELECT `id` FROM `documents`)");
        Schema::table("activity_document", function (Blueprint $table) {
            $table->foreign("document_id", "activity_document_document_id_foreign")->references("id")->on("documents")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `activity_operation` WHERE `activity_id` IS NOT NULL AND `activity_id` NOT IN (SELECT `id` FROM `activities`)");
        Schema::table("activity_operation", function (Blueprint $table) {
            $table->foreign("activity_id", "activity_operation_activity_id_foreign")->references("id")->on("activities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `activity_operation` WHERE `operation_id` IS NOT NULL AND `operation_id` NOT IN (SELECT `id` FROM `operations`)");
        Schema::table("activity_operation", function (Blueprint $table) {
            $table->foreign("operation_id", "activity_operation_operation_id_foreign")->references("id")->on("operations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `activity_process` WHERE `activity_id` IS NOT NULL AND `activity_id` NOT IN (SELECT `id` FROM `activities`)");
        Schema::table("activity_process", function (Blueprint $table) {
            $table->foreign("activity_id", "activity_process_activity_id_foreign")->references("id")->on("activities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `activity_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("activity_process", function (Blueprint $table) {
            $table->foreign("process_id", "activity_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `actor_operation` WHERE `actor_id` IS NOT NULL AND `actor_id` NOT IN (SELECT `id` FROM `actors`)");
        Schema::table("actor_operation", function (Blueprint $table) {
            $table->foreign("actor_id", "actor_operation_actor_id_foreign")->references("id")->on("actors")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `actor_operation` WHERE `operation_id` IS NOT NULL AND `operation_id` NOT IN (SELECT `id` FROM `operations`)");
        Schema::table("actor_operation", function (Blueprint $table) {
            $table->foreign("operation_id", "actor_operation_operation_id_foreign")->references("id")->on("operations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `admin_users` WHERE `domain_id` IS NOT NULL AND `domain_id` NOT IN (SELECT `id` FROM `domaine_ads`)");
        Schema::table("admin_users", function (Blueprint $table) {
            $table->foreign("domain_id", "admin_users_domain_id_foreign")->references("id")->on("domaine_ads")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `application_module_application_service` WHERE `application_module_id` IS NOT NULL AND `application_module_id` NOT IN (SELECT `id` FROM `application_modules`)");
        Schema::table("application_module_application_service", function (Blueprint $table) {
            $table->foreign("application_module_id", "application_module_application_application_module_id_fk")->references("id")->on("application_modules")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `application_module_application_service` WHERE `application_service_id` IS NOT NULL AND `application_service_id` NOT IN (SELECT `id` FROM `application_services`)");
        Schema::table("application_module_application_service", function (Blueprint $table) {
            $table->foreign("application_service_id", "application_module_application_application_service_id_fk")->references("id")->on("application_services")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `application_service_m_application` WHERE `application_service_id` IS NOT NULL AND `application_service_id` NOT IN (SELECT `id` FROM `application_services`)");
        Schema::table("application_service_m_application", function (Blueprint $table) {
            $table->foreign("application_service_id", "application_service_m_application_application_service_id_foreign")->references("id")->on("application_services")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `application_service_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("application_service_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "application_service_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `bay_wifi_terminal` WHERE `bay_id` IS NOT NULL AND `bay_id` NOT IN (SELECT `id` FROM `bays`)");
        Schema::table("bay_wifi_terminal", function (Blueprint $table) {
            $table->foreign("bay_id", "bay_wifi_terminal_bay_id_foreign")->references("id")->on("bays")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `bay_wifi_terminal` WHERE `wifi_terminal_id` IS NOT NULL AND `wifi_terminal_id` NOT IN (SELECT `id` FROM `wifi_terminals`)");
        Schema::table("bay_wifi_terminal", function (Blueprint $table) {
            $table->foreign("wifi_terminal_id", "bay_wifi_terminal_wifi_terminal_id_foreign")->references("id")->on("wifi_terminals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `cartographer_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("cartographer_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "cartographer_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `certificate_logical_server` WHERE `certificate_id` IS NOT NULL AND `certificate_id` NOT IN (SELECT `id` FROM `certificates`)");
        Schema::table("certificate_logical_server", function (Blueprint $table) {
            $table->foreign("certificate_id", "certificate_logical_server_certificate_id_foreign")->references("id")->on("certificates")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `certificate_logical_server` WHERE `logical_server_id` IS NOT NULL AND `logical_server_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("certificate_logical_server", function (Blueprint $table) {
            $table->foreign("logical_server_id", "certificate_logical_server_logical_server_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `certificate_m_application` WHERE `certificate_id` IS NOT NULL AND `certificate_id` NOT IN (SELECT `id` FROM `certificates`)");
        Schema::table("certificate_m_application", function (Blueprint $table) {
            $table->foreign("certificate_id", "certificate_m_application_certificate_id_foreign")->references("id")->on("certificates")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `certificate_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("certificate_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "certificate_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_document` WHERE `data_processing_id` IS NOT NULL AND `data_processing_id` NOT IN (SELECT `id` FROM `data_processing`)");
        Schema::table("data_processing_document", function (Blueprint $table) {
            $table->foreign("data_processing_id", "data_processing_document_data_processing_id_foreign")->references("id")->on("data_processing")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_document` WHERE `document_id` IS NOT NULL AND `document_id` NOT IN (SELECT `id` FROM `documents`)");
        Schema::table("data_processing_document", function (Blueprint $table) {
            $table->foreign("document_id", "data_processing_document_document_id_foreign")->references("id")->on("documents")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_information` WHERE `data_processing_id` IS NOT NULL AND `data_processing_id` NOT IN (SELECT `id` FROM `data_processing`)");
        Schema::table("data_processing_information", function (Blueprint $table) {
            $table->foreign("data_processing_id", "data_processing_information_data_processing_id_foreign")->references("id")->on("data_processing")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_information` WHERE `information_id` IS NOT NULL AND `information_id` NOT IN (SELECT `id` FROM `information`)");
        Schema::table("data_processing_information", function (Blueprint $table) {
            $table->foreign("information_id", "data_processing_information_information_id_foreign")->references("id")->on("information")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("data_processing_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "data_processing_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_m_application` WHERE `data_processing_id` IS NOT NULL AND `data_processing_id` NOT IN (SELECT `id` FROM `data_processing`)");
        Schema::table("data_processing_m_application", function (Blueprint $table) {
            $table->foreign("data_processing_id", "data_processing_m_application_data_processing_id_foreign")->references("id")->on("data_processing")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_process` WHERE `data_processing_id` IS NOT NULL AND `data_processing_id` NOT IN (SELECT `id` FROM `data_processing`)");
        Schema::table("data_processing_process", function (Blueprint $table) {
            $table->foreign("data_processing_id", "data_processing_process_data_processing_id_foreign")->references("id")->on("data_processing")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `data_processing_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("data_processing_process", function (Blueprint $table) {
            $table->foreign("process_id", "data_processing_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_entity` WHERE `database_id` IS NOT NULL AND `database_id` NOT IN (SELECT `id` FROM `databases`)");
        Schema::table("database_entity", function (Blueprint $table) {
            $table->foreign("database_id", "database_entity_database_id_foreign")->references("id")->on("databases")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_entity` WHERE `entity_id` IS NOT NULL AND `entity_id` NOT IN (SELECT `id` FROM `entities`)");
        Schema::table("database_entity", function (Blueprint $table) {
            $table->foreign("entity_id", "database_entity_entity_id_foreign")->references("id")->on("entities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_information` WHERE `database_id` IS NOT NULL AND `database_id` NOT IN (SELECT `id` FROM `databases`)");
        Schema::table("database_information", function (Blueprint $table) {
            $table->foreign("database_id", "database_information_database_id_foreign")->references("id")->on("databases")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_information` WHERE `information_id` IS NOT NULL AND `information_id` NOT IN (SELECT `id` FROM `information`)");
        Schema::table("database_information", function (Blueprint $table) {
            $table->foreign("information_id", "database_information_information_id_foreign")->references("id")->on("information")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_m_application` WHERE `database_id` IS NOT NULL AND `database_id` NOT IN (SELECT `id` FROM `databases`)");
        Schema::table("database_m_application", function (Blueprint $table) {
            $table->foreign("database_id", "database_m_application_database_id_foreign")->references("id")->on("databases")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `database_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("database_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "database_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `document_logical_server` WHERE `document_id` IS NOT NULL AND `document_id` NOT IN (SELECT `id` FROM `documents`)");
        Schema::table("document_logical_server", function (Blueprint $table) {
            $table->foreign("document_id", "document_logical_server_document_id_foreign")->references("id")->on("documents")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `document_logical_server` WHERE `logical_server_id` IS NOT NULL AND `logical_server_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("document_logical_server", function (Blueprint $table) {
            $table->foreign("logical_server_id", "document_logical_server_logical_server_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `document_relation` WHERE `document_id` IS NOT NULL AND `document_id` NOT IN (SELECT `id` FROM `documents`)");
        Schema::table("document_relation", function (Blueprint $table) {
            $table->foreign("document_id", "document_relation_document_id_foreign")->references("id")->on("documents")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `document_relation` WHERE `relation_id` IS NOT NULL AND `relation_id` NOT IN (SELECT `id` FROM `relations`)");
        Schema::table("document_relation", function (Blueprint $table) {
            $table->foreign("relation_id", "document_relation_relation_id_foreign")->references("id")->on("relations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `domaine_ad_forest_ad` WHERE `domaine_ad_id` IS NOT NULL AND `domaine_ad_id` NOT IN (SELECT `id` FROM `domaine_ads`)");
        Schema::table("domaine_ad_forest_ad", function (Blueprint $table) {
            $table->foreign("domaine_ad_id", "domaine_ad_forest_ad_domaine_ad_id_foreign")->references("id")->on("domaine_ads")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `domaine_ad_forest_ad` WHERE `forest_ad_id` IS NOT NULL AND `forest_ad_id` NOT IN (SELECT `id` FROM `forest_ads`)");
        Schema::table("domaine_ad_forest_ad", function (Blueprint $table) {
            $table->foreign("forest_ad_id", "domaine_ad_forest_ad_forest_ad_id_foreign")->references("id")->on("forest_ads")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_document` WHERE `document_id` IS NOT NULL AND `document_id` NOT IN (SELECT `id` FROM `documents`)");
        Schema::table("entity_document", function (Blueprint $table) {
            $table->foreign("document_id", "entity_document_document_id_foreign")->references("id")->on("documents")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_document` WHERE `entity_id` IS NOT NULL AND `entity_id` NOT IN (SELECT `id` FROM `entities`)");
        Schema::table("entity_document", function (Blueprint $table) {
            $table->foreign("entity_id", "entity_document_entity_id_foreign")->references("id")->on("entities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_m_application` WHERE `entity_id` IS NOT NULL AND `entity_id` NOT IN (SELECT `id` FROM `entities`)");
        Schema::table("entity_m_application", function (Blueprint $table) {
            $table->foreign("entity_id", "entity_m_application_entity_id_foreign")->references("id")->on("entities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("entity_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "entity_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_process` WHERE `entity_id` IS NOT NULL AND `entity_id` NOT IN (SELECT `id` FROM `entities`)");
        Schema::table("entity_process", function (Blueprint $table) {
            $table->foreign("entity_id", "entity_process_entity_id_foreign")->references("id")->on("entities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `entity_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("entity_process", function (Blueprint $table) {
            $table->foreign("process_id", "entity_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `external_connected_entities` WHERE `entity_id` IS NOT NULL AND `entity_id` NOT IN (SELECT `id` FROM `entities`)");
        Schema::table("external_connected_entities", function (Blueprint $table) {
            $table->foreign("entity_id", "external_connected_entities_entity_id_foreign")->references("id")->on("entities")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `external_connected_entities` WHERE `network_id` IS NOT NULL AND `network_id` NOT IN (SELECT `id` FROM `networks`)");
        Schema::table("external_connected_entities", function (Blueprint $table) {
            $table->foreign("network_id", "external_connected_entities_network_id_foreign")->references("id")->on("networks")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `information_process` WHERE `information_id` IS NOT NULL AND `information_id` NOT IN (SELECT `id` FROM `information`)");
        Schema::table("information_process", function (Blueprint $table) {
            $table->foreign("information_id", "information_process_information_id_foreign")->references("id")->on("information")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `information_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("information_process", function (Blueprint $table) {
            $table->foreign("process_id", "information_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `lan_man` WHERE `lan_id` IS NOT NULL AND `lan_id` NOT IN (SELECT `id` FROM `lans`)");
        Schema::table("lan_man", function (Blueprint $table) {
            $table->foreign("lan_id", "lan_man_lan_id_foreign")->references("id")->on("lans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `lan_man` WHERE `man_id` IS NOT NULL AND `man_id` NOT IN (SELECT `id` FROM `mans`)");
        Schema::table("lan_man", function (Blueprint $table) {
            $table->foreign("man_id", "lan_man_man_id_foreign")->references("id")->on("mans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `lan_wan` WHERE `lan_id` IS NOT NULL AND `lan_id` NOT IN (SELECT `id` FROM `lans`)");
        Schema::table("lan_wan", function (Blueprint $table) {
            $table->foreign("lan_id", "lan_wan_lan_id_foreign")->references("id")->on("lans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `lan_wan` WHERE `wan_id` IS NOT NULL AND `wan_id` NOT IN (SELECT `id` FROM `wans`)");
        Schema::table("lan_wan", function (Blueprint $table) {
            $table->foreign("wan_id", "lan_wan_wan_id_foreign")->references("id")->on("wans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_flows` WHERE `router_id` IS NOT NULL AND `router_id` NOT IN (SELECT `id` FROM `routers`)");
        Schema::table("logical_flows", function (Blueprint $table) {
            $table->foreign("router_id", "logical_flows_router_id_foreign")->references("id")->on("routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_server_m_application` WHERE `logical_server_id` IS NOT NULL AND `logical_server_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("logical_server_m_application", function (Blueprint $table) {
            $table->foreign("logical_server_id", "logical_server_m_application_logical_server_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_server_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("logical_server_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "logical_server_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_server_physical_server` WHERE `logical_server_id` IS NOT NULL AND `logical_server_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("logical_server_physical_server", function (Blueprint $table) {
            $table->foreign("logical_server_id", "logical_server_physical_server_logical_server_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_server_physical_server` WHERE `physical_server_id` IS NOT NULL AND `physical_server_id` NOT IN (SELECT `id` FROM `physical_servers`)");
        Schema::table("logical_server_physical_server", function (Blueprint $table) {
            $table->foreign("physical_server_id", "logical_server_physical_server_physical_server_id_foreign")->references("id")->on("physical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `logical_servers` WHERE `cluster_id` IS NOT NULL AND `cluster_id` NOT IN (SELECT `id` FROM `clusters`)");
        Schema::table("logical_servers", function (Blueprint $table) {
            $table->foreign("cluster_id", "logical_servers_cluster_id_foreign")->references("id")->on("clusters")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_events` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("m_application_events", function (Blueprint $table) {
            $table->foreign("m_application_id", "m_application_events_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_peripheral` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("m_application_peripheral", function (Blueprint $table) {
            $table->foreign("m_application_id", "m_application_peripheral_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_peripheral` WHERE `peripheral_id` IS NOT NULL AND `peripheral_id` NOT IN (SELECT `id` FROM `peripherals`)");
        Schema::table("m_application_peripheral", function (Blueprint $table) {
            $table->foreign("peripheral_id", "m_application_peripheral_peripheral_id_foreign")->references("id")->on("peripherals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_physical_server` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("m_application_physical_server", function (Blueprint $table) {
            $table->foreign("m_application_id", "m_application_physical_server_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_physical_server` WHERE `physical_server_id` IS NOT NULL AND `physical_server_id` NOT IN (SELECT `id` FROM `physical_servers`)");
        Schema::table("m_application_physical_server", function (Blueprint $table) {
            $table->foreign("physical_server_id", "m_application_physical_server_physical_server_id_foreign")->references("id")->on("physical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_process` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("m_application_process", function (Blueprint $table) {
            $table->foreign("m_application_id", "m_application_process_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("m_application_process", function (Blueprint $table) {
            $table->foreign("process_id", "m_application_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_workstation` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("m_application_workstation", function (Blueprint $table) {
            $table->foreign("m_application_id", "m_application_workstation_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `m_application_workstation` WHERE `workstation_id` IS NOT NULL AND `workstation_id` NOT IN (SELECT `id` FROM `workstations`)");
        Schema::table("m_application_workstation", function (Blueprint $table) {
            $table->foreign("workstation_id", "m_application_workstation_workstation_id_foreign")->references("id")->on("workstations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `man_wan` WHERE `man_id` IS NOT NULL AND `man_id` NOT IN (SELECT `id` FROM `mans`)");
        Schema::table("man_wan", function (Blueprint $table) {
            $table->foreign("man_id", "man_wan_man_id_foreign")->references("id")->on("mans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `man_wan` WHERE `wan_id` IS NOT NULL AND `wan_id` NOT IN (SELECT `id` FROM `wans`)");
        Schema::table("man_wan", function (Blueprint $table) {
            $table->foreign("wan_id", "man_wan_wan_id_foreign")->references("id")->on("wans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `network_switch_physical_switch` WHERE `network_switch_id` IS NOT NULL AND `network_switch_id` NOT IN (SELECT `id` FROM `network_switches`)");
        Schema::table("network_switch_physical_switch", function (Blueprint $table) {
            $table->foreign("network_switch_id", "network_switch_physical_switch_network_switch_id_foreign")->references("id")->on("network_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `network_switch_physical_switch` WHERE `physical_switch_id` IS NOT NULL AND `physical_switch_id` NOT IN (SELECT `id` FROM `physical_switches`)");
        Schema::table("network_switch_physical_switch", function (Blueprint $table) {
            $table->foreign("physical_switch_id", "network_switch_physical_switch_physical_switch_id_foreign")->references("id")->on("physical_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `operation_task` WHERE `operation_id` IS NOT NULL AND `operation_id` NOT IN (SELECT `id` FROM `operations`)");
        Schema::table("operation_task", function (Blueprint $table) {
            $table->foreign("operation_id", "operation_task_operation_id_foreign")->references("id")->on("operations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `operation_task` WHERE `task_id` IS NOT NULL AND `task_id` NOT IN (SELECT `id` FROM `tasks`)");
        Schema::table("operation_task", function (Blueprint $table) {
            $table->foreign("task_id", "operation_task_task_id_foreign")->references("id")->on("tasks")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `operations` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("operations", function (Blueprint $table) {
            $table->foreign("process_id", "operations_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `permission_role` WHERE `permission_id` IS NOT NULL AND `permission_id` NOT IN (SELECT `id` FROM `permissions`)");
        Schema::table("permission_role", function (Blueprint $table) {
            $table->foreign("permission_id", "permission_role_permission_id_foreign")->references("id")->on("permissions")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `permission_role` WHERE `role_id` IS NOT NULL AND `role_id` NOT IN (SELECT `id` FROM `roles`)");
        Schema::table("permission_role", function (Blueprint $table) {
            $table->foreign("role_id", "permission_role_role_id_foreign")->references("id")->on("roles")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `logical_server_dest_id` IS NOT NULL AND `logical_server_dest_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("logical_server_dest_id", "physical_links_logical_server_dest_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `logical_server_src_id` IS NOT NULL AND `logical_server_src_id` NOT IN (SELECT `id` FROM `logical_servers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("logical_server_src_id", "physical_links_logical_server_src_id_foreign")->references("id")->on("logical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `network_switch_dest_id` IS NOT NULL AND `network_switch_dest_id` NOT IN (SELECT `id` FROM `network_switches`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("network_switch_dest_id", "physical_links_network_switch_dest_id_foreign")->references("id")->on("network_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `network_switch_src_id` IS NOT NULL AND `network_switch_src_id` NOT IN (SELECT `id` FROM `network_switches`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("network_switch_src_id", "physical_links_network_switch_src_id_foreign")->references("id")->on("network_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `peripheral_dest_id` IS NOT NULL AND `peripheral_dest_id` NOT IN (SELECT `id` FROM `peripherals`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("peripheral_dest_id", "physical_links_peripheral_dest_id_foreign")->references("id")->on("peripherals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `peripheral_src_id` IS NOT NULL AND `peripheral_src_id` NOT IN (SELECT `id` FROM `peripherals`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("peripheral_src_id", "physical_links_peripheral_src_id_foreign")->references("id")->on("peripherals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `phone_dest_id` IS NOT NULL AND `phone_dest_id` NOT IN (SELECT `id` FROM `phones`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("phone_dest_id", "physical_links_phone_dest_id_foreign")->references("id")->on("phones")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `phone_src_id` IS NOT NULL AND `phone_src_id` NOT IN (SELECT `id` FROM `phones`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("phone_src_id", "physical_links_phone_src_id_foreign")->references("id")->on("phones")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_router_dest_id` IS NOT NULL AND `physical_router_dest_id` NOT IN (SELECT `id` FROM `physical_routers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_router_dest_id", "physical_links_physical_router_dest_id_foreign")->references("id")->on("physical_routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_router_src_id` IS NOT NULL AND `physical_router_src_id` NOT IN (SELECT `id` FROM `physical_routers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_router_src_id", "physical_links_physical_router_src_id_foreign")->references("id")->on("physical_routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_security_device_dest_id` IS NOT NULL AND `physical_security_device_dest_id` NOT IN (SELECT `id` FROM `physical_security_devices`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_security_device_dest_id", "physical_links_physical_security_device_dest_id_foreign")->references("id")->on("physical_security_devices")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_security_device_src_id` IS NOT NULL AND `physical_security_device_src_id` NOT IN (SELECT `id` FROM `physical_security_devices`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_security_device_src_id", "physical_links_physical_security_device_src_id_foreign")->references("id")->on("physical_security_devices")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_server_dest_id` IS NOT NULL AND `physical_server_dest_id` NOT IN (SELECT `id` FROM `physical_servers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_server_dest_id", "physical_links_physical_server_dest_id_foreign")->references("id")->on("physical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_server_src_id` IS NOT NULL AND `physical_server_src_id` NOT IN (SELECT `id` FROM `physical_servers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_server_src_id", "physical_links_physical_server_src_id_foreign")->references("id")->on("physical_servers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_switch_dest_id` IS NOT NULL AND `physical_switch_dest_id` NOT IN (SELECT `id` FROM `physical_switches`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_switch_dest_id", "physical_links_physical_switch_dest_id_foreign")->references("id")->on("physical_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `physical_switch_src_id` IS NOT NULL AND `physical_switch_src_id` NOT IN (SELECT `id` FROM `physical_switches`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("physical_switch_src_id", "physical_links_physical_switch_src_id_foreign")->references("id")->on("physical_switches")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `router_dest_id` IS NOT NULL AND `router_dest_id` NOT IN (SELECT `id` FROM `routers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("router_dest_id", "physical_links_router_dest_id_foreign")->references("id")->on("routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `router_src_id` IS NOT NULL AND `router_src_id` NOT IN (SELECT `id` FROM `routers`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("router_src_id", "physical_links_router_src_id_foreign")->references("id")->on("routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `storage_device_dest_id` IS NOT NULL AND `storage_device_dest_id` NOT IN (SELECT `id` FROM `storage_devices`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("storage_device_dest_id", "physical_links_storage_device_dest_id_foreign")->references("id")->on("storage_devices")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `storage_device_src_id` IS NOT NULL AND `storage_device_src_id` NOT IN (SELECT `id` FROM `storage_devices`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("storage_device_src_id", "physical_links_storage_device_src_id_foreign")->references("id")->on("storage_devices")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `wifi_terminal_dest_id` IS NOT NULL AND `wifi_terminal_dest_id` NOT IN (SELECT `id` FROM `wifi_terminals`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("wifi_terminal_dest_id", "physical_links_wifi_terminal_dest_id_foreign")->references("id")->on("wifi_terminals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `wifi_terminal_src_id` IS NOT NULL AND `wifi_terminal_src_id` NOT IN (SELECT `id` FROM `wifi_terminals`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("wifi_terminal_src_id", "physical_links_wifi_terminal_src_id_foreign")->references("id")->on("wifi_terminals")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `workstation_dest_id` IS NOT NULL AND `workstation_dest_id` NOT IN (SELECT `id` FROM `workstations`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("workstation_dest_id", "physical_links_workstation_dest_id_foreign")->references("id")->on("workstations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_links` WHERE `workstation_src_id` IS NOT NULL AND `workstation_src_id` NOT IN (SELECT `id` FROM `workstations`)");
        Schema::table("physical_links", function (Blueprint $table) {
            $table->foreign("workstation_src_id", "physical_links_workstation_src_id_foreign")->references("id")->on("workstations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_router_router` WHERE `physical_router_id` IS NOT NULL AND `physical_router_id` NOT IN (SELECT `id` FROM `physical_routers`)");
        Schema::table("physical_router_router", function (Blueprint $table) {
            $table->foreign("physical_router_id", "physical_router_router_physical_router_id_foreign")->references("id")->on("physical_routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_router_router` WHERE `router_id` IS NOT NULL AND `router_id` NOT IN (SELECT `id` FROM `routers`)");
        Schema::table("physical_router_router", function (Blueprint $table) {
            $table->foreign("router_id", "physical_router_router_router_id_foreign")->references("id")->on("routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_router_vlan` WHERE `physical_router_id` IS NOT NULL AND `physical_router_id` NOT IN (SELECT `id` FROM `physical_routers`)");
        Schema::table("physical_router_vlan", function (Blueprint $table) {
            $table->foreign("physical_router_id", "physical_router_vlan_physical_router_id_foreign")->references("id")->on("physical_routers")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_router_vlan` WHERE `vlan_id` IS NOT NULL AND `vlan_id` NOT IN (SELECT `id` FROM `vlans`)");
        Schema::table("physical_router_vlan", function (Blueprint $table) {
            $table->foreign("vlan_id", "physical_router_vlan_vlan_id_foreign")->references("id")->on("vlans")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `physical_servers` WHERE `cluster_id` IS NOT NULL AND `cluster_id` NOT IN (SELECT `id` FROM `clusters`)");
        Schema::table("physical_servers", function (Blueprint $table) {
            $table->foreign("cluster_id", "physical_servers_cluster_id_foreign")->references("id")->on("clusters")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `relation_values` WHERE `relation_id` IS NOT NULL AND `relation_id` NOT IN (SELECT `id` FROM `relations`)");
        Schema::table("relation_values", function (Blueprint $table) {
            $table->foreign("relation_id", "relation_values_relation_id_foreign")->references("id")->on("relations")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `role_user` WHERE `role_id` IS NOT NULL AND `role_id` NOT IN (SELECT `id` FROM `roles`)");
        Schema::table("role_user", function (Blueprint $table) {
            $table->foreign("role_id", "role_user_role_id_foreign")->references("id")->on("roles")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `role_user` WHERE `user_id` IS NOT NULL AND `user_id` NOT IN (SELECT `id` FROM `users`)");
        Schema::table("role_user", function (Blueprint $table) {
            $table->foreign("user_id", "role_user_user_id_foreign")->references("id")->on("users")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `security_control_m_application` WHERE `m_application_id` IS NOT NULL AND `m_application_id` NOT IN (SELECT `id` FROM `m_applications`)");
        Schema::table("security_control_m_application", function (Blueprint $table) {
            $table->foreign("m_application_id", "security_control_m_application_m_application_id_foreign")->references("id")->on("m_applications")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `security_control_m_application` WHERE `security_control_id` IS NOT NULL AND `security_control_id` NOT IN (SELECT `id` FROM `security_controls`)");
        Schema::table("security_control_m_application", function (Blueprint $table) {
            $table->foreign("security_control_id", "security_control_m_application_security_control_id_foreign")->references("id")->on("security_controls")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `security_control_process` WHERE `process_id` IS NOT NULL AND `process_id` NOT IN (SELECT `id` FROM `processes`)");
        Schema::table("security_control_process", function (Blueprint $table) {
            $table->foreign("process_id", "security_control_process_process_id_foreign")->references("id")->on("processes")->onDelete("restrict");
        });
        DB::statement("DELETE FROM `security_control_process` WHERE `security_control_id` IS NOT NULL AND `security_control_id` NOT IN (SELECT `id` FROM `security_controls`)");
        Schema::table("security_control_process", function (Blueprint $table) {
            $table->foreign("security_control_id", "security_control_process_security_control_id_foreign")->references("id")->on("security_controls")->onDelete("restrict");
        });
    }

    public function down(): void
    {
        // No Rollback !
    }
};

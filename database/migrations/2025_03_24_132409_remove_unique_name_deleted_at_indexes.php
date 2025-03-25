<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("activities", function (Blueprint $table) {
            $table->dropUnique("activities_name_unique");
        });
        Schema::table("actors", function (Blueprint $table) {
            $table->dropUnique("actors_name_unique");
        });
        Schema::table("application_blocks", function (Blueprint $table) {
            $table->dropUnique("application_blocks_name_unique");
        });
        Schema::table("application_modules", function (Blueprint $table) {
            $table->dropUnique("application_modules_name_unique");
        });
        Schema::table("application_services", function (Blueprint $table) {
            $table->dropUnique("application_services_name_unique");
        });
        Schema::table("dhcp_servers", function (Blueprint $table) {
            $table->dropUnique("dhcp_servers_name_unique");
        });
        Schema::table("dnsservers", function (Blueprint $table) {
            $table->dropUnique("dnsservers_name_unique");
        });
        Schema::table("domaine_ads", function (Blueprint $table) {
            $table->dropUnique("domaine_ads_name_unique");
        });
        Schema::table("gateways", function (Blueprint $table) {
            $table->dropUnique("gateways_name_unique");
        });
        Schema::table("information", function (Blueprint $table) {
            $table->dropUnique("information_name_unique");
        });
        Schema::table("lans", function (Blueprint $table) {
            $table->dropUnique("lans_name_unique");
        });
        Schema::table("macro_processuses", function (Blueprint $table) {
            $table->dropUnique("macro_processuses_name_unique");
        });
        Schema::table("mans", function (Blueprint $table) {
            $table->dropUnique("men_name_unique");
        });
        Schema::table("network_switches", function (Blueprint $table) {
            $table->dropUnique("network_switches_name_unique");
        });
        Schema::table("networks", function (Blueprint $table) {
            $table->dropUnique("networks_name_unique");
        });
        Schema::table("security_devices", function (Blueprint $table) {
            $table->dropUnique("security_devices_name_unique");
        });
        Schema::table("tasks", function (Blueprint $table) {
            $table->dropUnique("tasks_nom_unique");
        });
        Schema::table("vlans", function (Blueprint $table) {
            $table->dropUnique("vlans_name_unique");
        });
        Schema::table("zone_admins", function (Blueprint $table) {
            $table->dropUnique("zone_admins_name_unique");
        });
        Schema::table("bays", function (Blueprint $table) {
            $table->dropUnique("bays_name_unique");
        });
        Schema::table("buildings", function (Blueprint $table) {
            $table->dropUnique("buildings_name_unique");
        });
        Schema::table("databases", function (Blueprint $table) {
            $table->dropUnique("databases_name_unique");
        });
        Schema::table("forest_ads", function (Blueprint $table) {
            $table->dropUnique("forest_ads_name_unique");
        });
        Schema::table("phones", function (Blueprint $table) {
            $table->dropUnique("phones_name_unique");
        });
        Schema::table("physical_security_devices", function (Blueprint $table) {
            $table->dropUnique("physical_security_devices_name_unique");
        });
        Schema::table("physical_switches", function (Blueprint $table) {
            $table->dropUnique("physical_switches_name_unique");
        });
        Schema::table("processes", function (Blueprint $table) {
            $table->dropUnique("processes_identifiant_unique");
        });
        Schema::table("storage_devices", function (Blueprint $table) {
            $table->dropUnique("storage_devices_name_unique");
        });
        Schema::table("wifi_terminals", function (Blueprint $table) {
            $table->dropUnique("wifi_terminals_name_unique");
        });
        Schema::table("certificates", function (Blueprint $table) {
            $table->dropUnique("certificate_name_unique");
        });
        Schema::table("subnetworks", function (Blueprint $table) {
            $table->dropUnique("subnetwords_name_unique");
        });
        Schema::table("physical_routers", function (Blueprint $table) {
            $table->dropUnique("name");
        });
        Schema::table("operations", function (Blueprint $table) {
            $table->dropUnique("operations_name_unique");
        });
        Schema::table("external_connected_entities", function (Blueprint $table) {
            $table->dropUnique("external_connected_entities_name_unique");
        });
        Schema::table("security_controls", function (Blueprint $table) {
            $table->dropUnique("security_controls_name_unique");
        });
        try {
        Schema::table("clusters", function (Blueprint $table) {
            $table->dropUnique("cluster_name_unique");
        });
        } catch(err) { }
        Schema::table("physical_servers", function (Blueprint $table) {
            $table->dropUnique("physical_servers_name_unique");
        });
        Schema::table("routers", function (Blueprint $table) {
            $table->dropUnique("routers_name_unique");
        });
        Schema::table("logical_servers", function (Blueprint $table) {
            $table->dropUnique("logical_servers_name_unique");
        });
        Schema::table("m_applications", function (Blueprint $table) {
            $table->dropUnique("m_applications_name_unique");
        });
        Schema::table("workstations", function (Blueprint $table) {
            $table->dropUnique("workstations_name_unique");
        });
        Schema::table("peripherals", function (Blueprint $table) {
            $table->dropUnique("peripherals_name_unique");
        });
        Schema::table("sites", function (Blueprint $table) {
            $table->dropUnique("sites_name_unique");
        });
        try {
        Schema::table("entities", function (Blueprint $table) {
            $table->dropUnique("entities_name_unique");
        });
        } catch(err) { }
    }

    public function down(): void
    {
        // Not Rollback
    }
};

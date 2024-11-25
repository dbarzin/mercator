CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "oauth_auth_codes"(
  "id" varchar not null,
  "user_id" integer not null,
  "client_id" integer not null,
  "scopes" text,
  "revoked" tinyint(1) not null,
  "expires_at" datetime,
  primary key("id")
);
CREATE INDEX "oauth_auth_codes_user_id_index" on "oauth_auth_codes"("user_id");
CREATE TABLE IF NOT EXISTS "oauth_access_tokens"(
  "id" varchar not null,
  "user_id" integer,
  "client_id" integer not null,
  "name" varchar,
  "scopes" text,
  "revoked" tinyint(1) not null,
  "created_at" datetime,
  "updated_at" datetime,
  "expires_at" datetime,
  primary key("id")
);
CREATE INDEX "oauth_access_tokens_user_id_index" on "oauth_access_tokens"(
  "user_id"
);
CREATE TABLE IF NOT EXISTS "oauth_refresh_tokens"(
  "id" varchar not null,
  "access_token_id" varchar not null,
  "revoked" tinyint(1) not null,
  "expires_at" datetime,
  primary key("id")
);
CREATE INDEX "oauth_refresh_tokens_access_token_id_index" on "oauth_refresh_tokens"(
  "access_token_id"
);
CREATE TABLE IF NOT EXISTS "oauth_clients"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "name" varchar not null,
  "secret" varchar,
  "provider" varchar,
  "redirect" text not null,
  "personal_access_client" tinyint(1) not null,
  "password_client" tinyint(1) not null,
  "revoked" tinyint(1) not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "oauth_clients_user_id_index" on "oauth_clients"("user_id");
CREATE TABLE IF NOT EXISTS "oauth_personal_access_clients"(
  "id" integer primary key autoincrement not null,
  "client_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" varchar not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE TABLE IF NOT EXISTS "activities"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "activities_name_unique" on "activities"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "actors"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "nature" varchar,
  "type" varchar,
  "contact" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "actors_name_unique" on "actors"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "application_blocks"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "responsible" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "application_blocks_name_unique" on "application_blocks"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "application_modules"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "application_modules_name_unique" on "application_modules"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "application_services"(
  "id" integer primary key autoincrement not null,
  "description" text,
  "exposition" varchar,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "application_services_name_unique" on "application_services"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "dhcp_servers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "address_ip" varchar
);
CREATE UNIQUE INDEX "dhcp_servers_name_unique" on "dhcp_servers"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "dnsservers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "address_ip" varchar
);
CREATE UNIQUE INDEX "dnsservers_name_unique" on "dnsservers"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "domaine_ads"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "domain_ctrl_cnt" integer,
  "user_count" integer,
  "machine_count" integer,
  "relation_inter_domaine" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "domaine_ads_name_unique" on "domaine_ads"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "gateways"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "ip" varchar,
  "authentification" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "gateways_name_unique" on "gateways"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "information"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "owner" varchar,
  "administrator" varchar,
  "storage" varchar,
  "security_need_c" integer,
  "sensitivity" varchar,
  "constraints" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer,
  "retention" varchar
);
CREATE UNIQUE INDEX "information_name_unique" on "information"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "lans"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "lans_name_unique" on "lans"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "macro_processuses"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "io_elements" text,
  "security_need_c" integer,
  "owner" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer
);
CREATE UNIQUE INDEX "macro_processuses_name_unique" on "macro_processuses"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "mans"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "men_name_unique" on "mans"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "media"(
  "id" integer primary key autoincrement not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  "collection_name" varchar not null,
  "name" varchar not null,
  "file_name" varchar not null,
  "mime_type" varchar,
  "disk" varchar not null,
  "size" integer not null,
  "manipulations" text not null,
  "custom_properties" text not null,
  "responsive_images" text not null,
  "order_column" integer,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "media_model_type_model_id_index" on "media"(
  "model_type",
  "model_id"
);
CREATE TABLE IF NOT EXISTS "network_switches"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "ip" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "network_switches_name_unique" on "network_switches"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "networks"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "protocol_type" varchar,
  "responsible" varchar,
  "responsible_sec" varchar,
  "security_need_c" integer,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer
);
CREATE UNIQUE INDEX "networks_name_unique" on "networks"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "password_resets"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime
);
CREATE INDEX "password_resets_email_index" on "password_resets"("email");
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "title" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" integer primary key autoincrement not null,
  "title" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "security_devices"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "vendor" varchar,
  "product" varchar,
  "version" varchar
);
CREATE UNIQUE INDEX "security_devices_name_unique" on "security_devices"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "tasks"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "tasks_nom_unique" on "tasks"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "email" varchar,
  "email_verified_at" datetime,
  "password" varchar,
  "remember_token" varchar,
  "granularity" integer,
  "language" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "guid" varchar,
  "domain" varchar
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email", "deleted_at");
CREATE TABLE IF NOT EXISTS "vlans"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "vlan_id" integer
);
CREATE UNIQUE INDEX "vlans_name_unique" on "vlans"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "wans"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "zone_admins"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "zone_admins_name_unique" on "zone_admins"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "activity_operation"(
  "activity_id" integer not null,
  "operation_id" integer not null,
  foreign key("activity_id") references "activities"("id") on delete CASCADE on update NO ACTION,
  foreign key("operation_id") references "operations"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "activity_id_fk_1472704" on "activity_operation"("activity_id");
CREATE INDEX "operation_id_fk_1472704" on "activity_operation"("operation_id");
CREATE TABLE IF NOT EXISTS "activity_process"(
  "process_id" integer not null,
  "activity_id" integer not null,
  foreign key("activity_id") references "activities"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "activity_id_fk_1627616" on "activity_process"("activity_id");
CREATE INDEX "process_id_fk_1627616" on "activity_process"("process_id");
CREATE TABLE IF NOT EXISTS "actor_operation"(
  "operation_id" integer not null,
  "actor_id" integer not null,
  foreign key("actor_id") references "actors"("id") on delete CASCADE on update NO ACTION,
  foreign key("operation_id") references "operations"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "actor_id_fk_1472680" on "actor_operation"("actor_id");
CREATE INDEX "operation_id_fk_1472680" on "actor_operation"("operation_id");
CREATE TABLE IF NOT EXISTS "annuaires"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "solution" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "zone_admin_id" integer,
  foreign key("zone_admin_id") references "zone_admins"("id") on delete NO ACTION on update NO ACTION
);
CREATE UNIQUE INDEX "annuaires_name_unique" on "annuaires"("name");
CREATE INDEX "zone_admin_fk_1482666" on "annuaires"("zone_admin_id");
CREATE TABLE IF NOT EXISTS "application_module_application_service"(
  "application_service_id" integer not null,
  "application_module_id" integer not null,
  foreign key("application_module_id") references "application_modules"("id") on delete CASCADE on update NO ACTION,
  foreign key("application_service_id") references "application_services"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "application_module_id_fk_1492414" on "application_module_application_service"(
  "application_module_id"
);
CREATE INDEX "application_service_id_fk_1492414" on "application_module_application_service"(
  "application_service_id"
);
CREATE TABLE IF NOT EXISTS "application_service_m_application"(
  "m_application_id" integer not null,
  "application_service_id" integer not null,
  foreign key("application_service_id") references "application_services"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "application_service_id_fk_1482585" on "application_service_m_application"(
  "application_service_id"
);
CREATE INDEX "m_application_id_fk_1482585" on "application_service_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "bay_wifi_terminal"(
  "wifi_terminal_id" integer not null,
  "bay_id" integer not null,
  foreign key("bay_id") references "bays"("id") on delete CASCADE on update NO ACTION,
  foreign key("wifi_terminal_id") references "wifi_terminals"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "bay_id_fk_1485509" on "bay_wifi_terminal"("bay_id");
CREATE INDEX "wifi_terminal_id_fk_1485509" on "bay_wifi_terminal"(
  "wifi_terminal_id"
);
CREATE TABLE IF NOT EXISTS "bays"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "room_id" integer,
  foreign key("room_id") references "buildings"("id") on delete NO ACTION on update NO ACTION
);
CREATE UNIQUE INDEX "bays_name_unique" on "bays"("name", "deleted_at");
CREATE INDEX "room_fk_1483441" on "bays"("room_id");
CREATE TABLE IF NOT EXISTS "buildings"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "attributes" varchar,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE UNIQUE INDEX "buildings_name_unique" on "buildings"(
  "name",
  "deleted_at"
);
CREATE INDEX "site_fk_1483431" on "buildings"("site_id");
CREATE TABLE IF NOT EXISTS "database_entity"(
  "database_id" integer not null,
  "entity_id" integer not null,
  foreign key("database_id") references "databases"("id") on delete CASCADE on update NO ACTION,
  foreign key("entity_id") references "entities"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "database_id_fk_1485563" on "database_entity"("database_id");
CREATE INDEX "entity_id_fk_1485563" on "database_entity"("entity_id");
CREATE TABLE IF NOT EXISTS "database_information"(
  "database_id" integer not null,
  "information_id" integer not null,
  foreign key("database_id") references "databases"("id") on delete CASCADE on update NO ACTION,
  foreign key("information_id") references "information"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "database_id_fk_1485570" on "database_information"("database_id");
CREATE INDEX "information_id_fk_1485570" on "database_information"(
  "information_id"
);
CREATE TABLE IF NOT EXISTS "database_m_application"(
  "m_application_id" integer not null,
  "database_id" integer not null,
  foreign key("database_id") references "databases"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "database_id_fk_1482586" on "database_m_application"(
  "database_id"
);
CREATE INDEX "m_application_id_fk_1482586" on "database_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "databases"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "responsible" varchar,
  "type" varchar,
  "security_need_c" integer,
  "external" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "entity_resp_id" integer,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer,
  foreign key("entity_resp_id") references "entities"("id") on delete NO ACTION on update NO ACTION
);
CREATE UNIQUE INDEX "databases_name_unique" on "databases"(
  "name",
  "deleted_at"
);
CREATE INDEX "entity_resp_fk_1485569" on "databases"("entity_resp_id");
CREATE TABLE IF NOT EXISTS "domaine_ad_forest_ad"(
  "forest_ad_id" integer not null,
  "domaine_ad_id" integer not null,
  foreign key("domaine_ad_id") references "domaine_ads"("id") on delete CASCADE on update NO ACTION,
  foreign key("forest_ad_id") references "forest_ads"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "domaine_ad_id_fk_1492084" on "domaine_ad_forest_ad"(
  "domaine_ad_id"
);
CREATE INDEX "forest_ad_id_fk_1492084" on "domaine_ad_forest_ad"(
  "forest_ad_id"
);
CREATE TABLE IF NOT EXISTS "entity_m_application"(
  "m_application_id" integer not null,
  "entity_id" integer not null,
  foreign key("entity_id") references "entities"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "entity_id_fk_1488611" on "entity_m_application"("entity_id");
CREATE INDEX "m_application_id_fk_1488611" on "entity_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "entity_process"(
  "process_id" integer not null,
  "entity_id" integer not null,
  foreign key("entity_id") references "entities"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "entity_id_fk_1627958" on "entity_process"("entity_id");
CREATE INDEX "process_id_fk_1627958" on "entity_process"("process_id");
CREATE TABLE IF NOT EXISTS "fluxes"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "application_source_id" integer,
  "service_source_id" integer,
  "module_source_id" integer,
  "database_source_id" integer,
  "application_dest_id" integer,
  "service_dest_id" integer,
  "module_dest_id" integer,
  "database_dest_id" integer,
  "crypted" tinyint(1),
  "bidirectional" tinyint(1),
  "nature" varchar,
  foreign key("application_dest_id") references "m_applications"("id") on delete NO ACTION on update NO ACTION,
  foreign key("application_source_id") references "m_applications"("id") on delete NO ACTION on update NO ACTION,
  foreign key("database_dest_id") references "databases"("id") on delete NO ACTION on update NO ACTION,
  foreign key("database_source_id") references "databases"("id") on delete NO ACTION on update NO ACTION,
  foreign key("module_dest_id") references "application_modules"("id") on delete NO ACTION on update NO ACTION,
  foreign key("module_source_id") references "application_modules"("id") on delete NO ACTION on update NO ACTION,
  foreign key("service_dest_id") references "application_services"("id") on delete NO ACTION on update NO ACTION,
  foreign key("service_source_id") references "application_services"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "application_dest_fk_1485549" on "fluxes"("application_dest_id");
CREATE INDEX "application_source_fk_1485545" on "fluxes"(
  "application_source_id"
);
CREATE INDEX "database_dest_fk_1485552" on "fluxes"("database_dest_id");
CREATE INDEX "database_source_fk_1485548" on "fluxes"("database_source_id");
CREATE INDEX "module_dest_fk_1485551" on "fluxes"("module_dest_id");
CREATE INDEX "module_source_fk_1485547" on "fluxes"("module_source_id");
CREATE INDEX "service_dest_fk_1485550" on "fluxes"("service_dest_id");
CREATE INDEX "service_source_fk_1485546" on "fluxes"("service_source_id");
CREATE TABLE IF NOT EXISTS "forest_ads"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "zone_admin_id" integer,
  foreign key("zone_admin_id") references "zone_admins"("id") on delete NO ACTION on update NO ACTION
);
CREATE UNIQUE INDEX "forest_ads_name_unique" on "forest_ads"(
  "name",
  "deleted_at"
);
CREATE INDEX "zone_admin_fk_1482667" on "forest_ads"("zone_admin_id");
CREATE TABLE IF NOT EXISTS "information_process"(
  "information_id" integer not null,
  "process_id" integer not null,
  foreign key("information_id") references "information"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "information_id_fk_1473025" on "information_process"(
  "information_id"
);
CREATE INDEX "process_id_fk_1473025" on "information_process"("process_id");
CREATE TABLE IF NOT EXISTS "lan_man"(
  "man_id" integer not null,
  "lan_id" integer not null,
  foreign key("lan_id") references "lans"("id") on delete CASCADE on update NO ACTION,
  foreign key("man_id") references "mans"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "lan_id_fk_1490345" on "lan_man"("lan_id");
CREATE INDEX "man_id_fk_1490345" on "lan_man"("man_id");
CREATE TABLE IF NOT EXISTS "lan_wan"(
  "wan_id" integer not null,
  "lan_id" integer not null,
  foreign key("lan_id") references "lans"("id") on delete CASCADE on update NO ACTION,
  foreign key("wan_id") references "wans"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "lan_id_fk_1490368" on "lan_wan"("lan_id");
CREATE INDEX "wan_id_fk_1490368" on "lan_wan"("wan_id");
CREATE TABLE IF NOT EXISTS "logical_server_m_application"(
  "m_application_id" integer not null,
  "logical_server_id" integer not null,
  foreign key("logical_server_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "logical_server_id_fk_1488616" on "logical_server_m_application"(
  "logical_server_id"
);
CREATE INDEX "m_application_id_fk_1488616" on "logical_server_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "logical_server_physical_server"(
  "logical_server_id" integer not null,
  "physical_server_id" integer not null,
  foreign key("logical_server_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION,
  foreign key("physical_server_id") references "physical_servers"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "logical_server_id_fk_1657961" on "logical_server_physical_server"(
  "logical_server_id"
);
CREATE INDEX "physical_server_id_fk_1657961" on "logical_server_physical_server"(
  "physical_server_id"
);
CREATE TABLE IF NOT EXISTS "m_application_process"(
  "m_application_id" integer not null,
  "process_id" integer not null,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "m_application_id_fk_1482573" on "m_application_process"(
  "m_application_id"
);
CREATE INDEX "process_id_fk_1482573" on "m_application_process"("process_id");
CREATE TABLE IF NOT EXISTS "man_wan"(
  "wan_id" integer not null,
  "man_id" integer not null,
  foreign key("man_id") references "mans"("id") on delete CASCADE on update NO ACTION,
  foreign key("wan_id") references "wans"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "man_id_fk_1490367" on "man_wan"("man_id");
CREATE INDEX "wan_id_fk_1490367" on "man_wan"("wan_id");
CREATE TABLE IF NOT EXISTS "operation_task"(
  "operation_id" integer not null,
  "task_id" integer not null,
  foreign key("operation_id") references "operations"("id") on delete CASCADE on update NO ACTION,
  foreign key("task_id") references "tasks"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "operation_id_fk_1472749" on "operation_task"("operation_id");
CREATE INDEX "task_id_fk_1472749" on "operation_task"("task_id");
CREATE TABLE IF NOT EXISTS "permission_role"(
  "role_id" integer not null,
  "permission_id" integer not null,
  foreign key("permission_id") references "permissions"("id") on delete CASCADE on update NO ACTION,
  foreign key("role_id") references "roles"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "permission_id_fk_1470794" on "permission_role"("permission_id");
CREATE INDEX "role_id_fk_1470794" on "permission_role"("role_id");
CREATE TABLE IF NOT EXISTS "phones"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "type" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "physical_switch_id" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "address_ip" varchar,
  foreign key("building_id") references "buildings"("id") on delete NO ACTION on update NO ACTION,
  foreign key("physical_switch_id") references "physical_switches"("id") on delete NO ACTION on update NO ACTION,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "building_fk_1485480" on "phones"("building_id");
CREATE UNIQUE INDEX "phones_name_unique" on "phones"("name", "deleted_at");
CREATE INDEX "physical_switch_fk_5738332" on "phones"("physical_switch_id");
CREATE INDEX "site_fk_1485479" on "phones"("site_id");
CREATE TABLE IF NOT EXISTS "physical_router_vlan"(
  "physical_router_id" integer not null,
  "vlan_id" integer not null,
  foreign key("physical_router_id") references "physical_routers"("id") on delete CASCADE on update NO ACTION,
  foreign key("vlan_id") references "vlans"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "physical_router_id_fk_1658250" on "physical_router_vlan"(
  "physical_router_id"
);
CREATE INDEX "vlan_id_fk_1658250" on "physical_router_vlan"("vlan_id");
CREATE TABLE IF NOT EXISTS "physical_security_devices"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "address_ip" varchar,
  foreign key("bay_id") references "bays"("id") on delete NO ACTION on update NO ACTION,
  foreign key("building_id") references "buildings"("id") on delete NO ACTION on update NO ACTION,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "bay_fk_1485519" on "physical_security_devices"("bay_id");
CREATE INDEX "building_fk_1485518" on "physical_security_devices"(
  "building_id"
);
CREATE UNIQUE INDEX "physical_security_devices_name_unique" on "physical_security_devices"(
  "name",
  "deleted_at"
);
CREATE INDEX "site_fk_1485517" on "physical_security_devices"("site_id");
CREATE TABLE IF NOT EXISTS "physical_switches"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "type" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  foreign key("bay_id") references "bays"("id") on delete NO ACTION on update NO ACTION,
  foreign key("building_id") references "buildings"("id") on delete NO ACTION on update NO ACTION,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "bay_fk_1485493" on "physical_switches"("bay_id");
CREATE INDEX "building_fk_1485489" on "physical_switches"("building_id");
CREATE UNIQUE INDEX "physical_switches_name_unique" on "physical_switches"(
  "name",
  "deleted_at"
);
CREATE INDEX "site_fk_1485488" on "physical_switches"("site_id");
CREATE TABLE IF NOT EXISTS "processes"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "owner" varchar,
  "security_need_c" integer,
  "in_out" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "macroprocess_id" integer,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer,
  foreign key("macroprocess_id") references "macro_processuses"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "process_fk_4342342" on "processes"("macroprocess_id");
CREATE UNIQUE INDEX "processes_identifiant_unique" on "processes"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "relations"(
  "id" integer primary key autoincrement not null,
  "importance" integer,
  "name" varchar not null,
  "type" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "source_id" integer not null,
  "destination_id" integer not null,
  "attributes" varchar,
  "reference" varchar,
  "responsible" varchar,
  "order_number" varchar,
  "active" tinyint(1) not null default '1',
  "start_date" date,
  "end_date" date,
  "comments" text,
  "security_need_c" integer,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer,
  foreign key("destination_id") references "entities"("id") on delete NO ACTION on update NO ACTION,
  foreign key("source_id") references "entities"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "destination_fk_1494373" on "relations"("destination_id");
CREATE INDEX "source_fk_1494372" on "relations"("source_id");
CREATE TABLE IF NOT EXISTS "role_user"(
  "user_id" integer not null,
  "role_id" integer not null,
  foreign key("role_id") references "roles"("id") on delete CASCADE on update NO ACTION,
  foreign key("user_id") references "users"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "role_id_fk_1470803" on "role_user"("role_id");
CREATE INDEX "user_id_fk_1470803" on "role_user"("user_id");
CREATE TABLE IF NOT EXISTS "storage_devices"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "physical_switch_id" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "address_ip" varchar,
  "type" varchar,
  foreign key("bay_id") references "bays"("id") on delete NO ACTION on update NO ACTION,
  foreign key("building_id") references "buildings"("id") on delete NO ACTION on update NO ACTION,
  foreign key("physical_switch_id") references "physical_switches"("id") on delete NO ACTION on update NO ACTION,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "bay_fk_1485363" on "storage_devices"("bay_id");
CREATE INDEX "building_fk_1485362" on "storage_devices"("building_id");
CREATE INDEX "physical_switch_fk_4025543" on "storage_devices"(
  "physical_switch_id"
);
CREATE INDEX "site_fk_1485361" on "storage_devices"("site_id");
CREATE UNIQUE INDEX "storage_devices_name_unique" on "storage_devices"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "wifi_terminals"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "type" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "physical_switch_id" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "address_ip" varchar,
  foreign key("building_id") references "buildings"("id") on delete NO ACTION on update NO ACTION,
  foreign key("physical_switch_id") references "physical_switches"("id") on delete NO ACTION on update NO ACTION,
  foreign key("site_id") references "sites"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "building_fk_1485508" on "wifi_terminals"("building_id");
CREATE INDEX "physical_switch_fk_593584" on "wifi_terminals"(
  "physical_switch_id"
);
CREATE INDEX "site_fk_1485507" on "wifi_terminals"("site_id");
CREATE UNIQUE INDEX "wifi_terminals_name_unique" on "wifi_terminals"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "certificates"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar,
  "description" text,
  "start_validity" date,
  "end_validity" date,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "status" integer,
  "last_notification" datetime
);
CREATE UNIQUE INDEX "certificate_name_unique" on "certificates"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "certificate_logical_server"(
  "certificate_id" integer not null,
  "logical_server_id" integer not null,
  foreign key("certificate_id") references "certificates"("id") on delete CASCADE on update NO ACTION,
  foreign key("logical_server_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "certificate_id_fk_9483453" on "certificate_logical_server"(
  "certificate_id"
);
CREATE INDEX "logical_server_id_fk_9483453" on "certificate_logical_server"(
  "logical_server_id"
);
CREATE TABLE IF NOT EXISTS "certificate_m_application"(
  "certificate_id" integer not null,
  "m_application_id" integer not null,
  foreign key("certificate_id") references "certificates"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "certificate_id_fk_4584393" on "certificate_m_application"(
  "certificate_id"
);
CREATE INDEX "m_application_id_fk_4584393s" on "certificate_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "subnetworks"(
  "id" integer primary key autoincrement not null,
  "description" text,
  "address" varchar,
  "ip_allocation_type" varchar,
  "responsible_exp" varchar,
  "dmz" varchar,
  "wifi" varchar,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "connected_subnets_id" integer,
  "gateway_id" integer,
  "zone" varchar,
  "vlan_id" integer,
  "network_id" integer,
  "default_gateway" varchar,
  foreign key("gateway_id") references gateways("id") on delete no action on update no action,
  foreign key("connected_subnets_id") references subnetworks("id") on delete no action on update no action,
  foreign key("vlan_id") references "vlans"("id") on delete NO ACTION on update NO ACTION,
  foreign key("network_id") references "networks"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "connected_subnets_fk_1483256" on "subnetworks"(
  "connected_subnets_id"
);
CREATE INDEX "gateway_fk_1492376" on "subnetworks"("gateway_id");
CREATE UNIQUE INDEX "subnetwords_name_unique" on "subnetworks"(
  "name",
  "deleted_at"
);
CREATE INDEX "vlan_fk_6844934" on "subnetworks"("vlan_id");
CREATE INDEX "network_fk_5476544" on "subnetworks"("network_id");
CREATE TABLE IF NOT EXISTS "physical_routers"(
  "id" integer primary key autoincrement not null,
  "description" text,
  "type" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "name" varchar not null,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  foreign key("site_id") references sites("id") on delete no action on update no action,
  foreign key("building_id") references buildings("id") on delete no action on update no action,
  foreign key("bay_id") references bays("id") on delete no action on update no action
);
CREATE INDEX "bay_fk_1485499" on "physical_routers"("bay_id");
CREATE INDEX "building_fk_1485498" on "physical_routers"("building_id");
CREATE UNIQUE INDEX "name" on "physical_routers"("name", "deleted_at");
CREATE INDEX "site_fk_1485497" on "physical_routers"("site_id");
CREATE TABLE IF NOT EXISTS "cartographer_m_application"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "m_application_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("user_id") references "users"("id"),
  foreign key("m_application_id") references "m_applications"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "m_application_events"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "m_application_id" integer not null,
  "message" text not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id"),
  foreign key("m_application_id") references "m_applications"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "m_application_workstation"(
  "m_application_id" integer not null,
  "workstation_id" integer not null,
  foreign key("workstation_id") references "workstations"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "m_application_id_fk_1486547" on "m_application_workstation"(
  "m_application_id"
);
CREATE INDEX "workstation_id_fk_1486547" on "m_application_workstation"(
  "workstation_id"
);
CREATE TABLE IF NOT EXISTS "operations"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "process_id" integer,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE UNIQUE INDEX "operations_name_unique" on "operations"(
  "name",
  "deleted_at"
);
CREATE INDEX "process_id_fk_7945129" on "operations"("process_id");
CREATE TABLE IF NOT EXISTS "external_connected_entities"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "contacts" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "description" text,
  "type" varchar,
  "entity_id" integer,
  "network_id" integer,
  "src" varchar,
  "dest" varchar,
  "src_desc" varchar,
  "dest_desc" varchar,
  foreign key("entity_id") references "entities"("id") on delete CASCADE on update NO ACTION,
  foreign key("network_id") references "networks"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "entity_id_fk_1295034" on "external_connected_entities"(
  "entity_id"
);
CREATE UNIQUE INDEX "external_connected_entities_name_unique" on "external_connected_entities"(
  "name",
  "deleted_at"
);
CREATE INDEX "network_id_fk_8596554" on "external_connected_entities"(
  "network_id"
);
CREATE TABLE IF NOT EXISTS "database_logical_server"(
  "database_id" integer not null,
  "logical_server_id" integer not null
);
CREATE INDEX "database_id_fk_1542475" on "database_logical_server"(
  "database_id"
);
CREATE INDEX "logical_server_id_fk_1542475" on "database_logical_server"(
  "logical_server_id"
);
CREATE TABLE IF NOT EXISTS "physical_links"(
  "id" integer primary key autoincrement not null,
  "src_port" varchar,
  "dest_port" varchar,
  "peripheral_src_id" integer,
  "phone_src_id" integer,
  "physical_router_src_id" integer,
  "physical_security_device_src_id" integer,
  "physical_server_src_id" integer,
  "physical_switch_src_id" integer,
  "storage_device_src_id" integer,
  "wifi_terminal_src_id" integer,
  "workstation_src_id" integer,
  "peripheral_dest_id" integer,
  "phone_dest_id" integer,
  "physical_router_dest_id" integer,
  "physical_security_device_dest_id" integer,
  "physical_server_dest_id" integer,
  "physical_switch_dest_id" integer,
  "storage_device_dest_id" integer,
  "wifi_terminal_dest_id" integer,
  "workstation_dest_id" integer,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "router_src_id" integer,
  "router_dest_id" integer,
  "network_switch_src_id" integer,
  "network_switch_dest_id" integer,
  "logical_server_src_id" integer,
  "logical_server_dest_id" integer,
  foreign key("workstation_dest_id") references workstations("id") on delete cascade on update no action,
  foreign key("wifi_terminal_dest_id") references wifi_terminals("id") on delete cascade on update no action,
  foreign key("storage_device_dest_id") references storage_devices("id") on delete cascade on update no action,
  foreign key("physical_switch_dest_id") references physical_switches("id") on delete cascade on update no action,
  foreign key("physical_server_dest_id") references physical_servers("id") on delete cascade on update no action,
  foreign key("physical_security_device_dest_id") references physical_security_devices("id") on delete cascade on update no action,
  foreign key("physical_router_dest_id") references physical_routers("id") on delete cascade on update no action,
  foreign key("phone_dest_id") references phones("id") on delete cascade on update no action,
  foreign key("peripheral_dest_id") references peripherals("id") on delete cascade on update no action,
  foreign key("workstation_src_id") references workstations("id") on delete cascade on update no action,
  foreign key("wifi_terminal_src_id") references wifi_terminals("id") on delete cascade on update no action,
  foreign key("storage_device_src_id") references storage_devices("id") on delete cascade on update no action,
  foreign key("physical_switch_src_id") references physical_switches("id") on delete cascade on update no action,
  foreign key("physical_server_src_id") references physical_servers("id") on delete cascade on update no action,
  foreign key("physical_security_device_src_id") references physical_security_devices("id") on delete cascade on update no action,
  foreign key("physical_router_src_id") references physical_routers("id") on delete cascade on update no action,
  foreign key("phone_src_id") references phones("id") on delete cascade on update no action,
  foreign key("peripheral_src_id") references peripherals("id") on delete cascade on update no action,
  foreign key("router_src_id") references "routers"("id") on delete CASCADE on update NO ACTION,
  foreign key("router_dest_id") references "routers"("id") on delete CASCADE on update NO ACTION,
  foreign key("network_switch_src_id") references "network_switches"("id") on delete CASCADE on update NO ACTION,
  foreign key("network_switch_dest_id") references "network_switches"("id") on delete CASCADE on update NO ACTION,
  foreign key("logical_server_src_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION,
  foreign key("logical_server_dest_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "peripheral_dest_id_fk" on "physical_links"("peripheral_dest_id");
CREATE INDEX "peripheral_src_id_fk" on "physical_links"("peripheral_src_id");
CREATE INDEX "phone_dest_id_fk" on "physical_links"("phone_dest_id");
CREATE INDEX "phone_src_id_fk" on "physical_links"("phone_src_id");
CREATE INDEX "physical_router_dest_id_fk" on "physical_links"(
  "physical_router_dest_id"
);
CREATE INDEX "physical_router_src_id_fk" on "physical_links"(
  "physical_router_src_id"
);
CREATE INDEX "physical_security_device_dest_id_fk" on "physical_links"(
  "physical_security_device_dest_id"
);
CREATE INDEX "physical_security_device_src_id_fk" on "physical_links"(
  "physical_security_device_src_id"
);
CREATE INDEX "physical_server_dest_id_fk" on "physical_links"(
  "physical_server_dest_id"
);
CREATE INDEX "physical_server_src_id_fk" on "physical_links"(
  "physical_server_src_id"
);
CREATE INDEX "physical_switch_dest_id_fk" on "physical_links"(
  "physical_switch_dest_id"
);
CREATE INDEX "physical_switch_src_id_fk" on "physical_links"(
  "physical_switch_src_id"
);
CREATE INDEX "storage_device_dest_id_fk" on "physical_links"(
  "storage_device_dest_id"
);
CREATE INDEX "storage_device_src_id_fk" on "physical_links"(
  "storage_device_src_id"
);
CREATE INDEX "wifi_terminal_dest_id_fk" on "physical_links"(
  "wifi_terminal_dest_id"
);
CREATE INDEX "wifi_terminal_src_id_fk" on "physical_links"(
  "wifi_terminal_src_id"
);
CREATE INDEX "workstation_dest_id_fk" on "physical_links"(
  "workstation_dest_id"
);
CREATE INDEX "workstation_src_id_fk" on "physical_links"("workstation_src_id");
CREATE INDEX "router_src_id_fk" on "physical_links"("router_src_id");
CREATE INDEX "router_dest_id_fk" on "physical_links"("router_dest_id");
CREATE INDEX "network_switches_src_id_fk" on "physical_links"(
  "network_switch_src_id"
);
CREATE INDEX "network_switches_dest_id_fk" on "physical_links"(
  "network_switch_dest_id"
);
CREATE INDEX "logical_server_src_id_fk" on "physical_links"(
  "logical_server_src_id"
);
CREATE INDEX "logical_server_dest_id_fk" on "physical_links"(
  "logical_server_dest_id"
);
CREATE TABLE IF NOT EXISTS "documents"(
  "id" integer primary key autoincrement not null,
  "filename" varchar not null,
  "mimetype" varchar not null,
  "size" integer not null,
  "hash" varchar not null,
  "deleted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "activity_document"(
  "activity_id" integer not null,
  "document_id" integer not null,
  foreign key("activity_id") references "activities"("id") on delete CASCADE on update NO ACTION,
  foreign key("document_id") references "documents"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "activity_id_fk_1472714" on "activity_document"("activity_id");
CREATE INDEX "operation_id_fk_1472714" on "activity_document"("document_id");
CREATE TABLE IF NOT EXISTS "entity_document"(
  "entity_id" integer not null,
  "document_id" integer not null,
  foreign key("entity_id") references "entities"("id") on delete CASCADE on update NO ACTION,
  foreign key("document_id") references "documents"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "activity_id_fk_4325433" on "entity_document"("entity_id");
CREATE INDEX "operation_id_fk_5837593" on "entity_document"("document_id");
CREATE TABLE IF NOT EXISTS "cpe_vendors"(
  "id" integer primary key autoincrement not null,
  "part" varchar not null,
  "name" varchar not null
);
CREATE UNIQUE INDEX "cpe_vendors_part_name_unique" on "cpe_vendors"(
  "part",
  "name"
);
CREATE TABLE IF NOT EXISTS "cpe_products"(
  "id" integer primary key autoincrement not null,
  "cpe_vendor_id" integer not null,
  "name" varchar not null,
  foreign key("cpe_vendor_id") references "cpe_vendors"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "cpe_product_fk_1485479" on "cpe_products"("cpe_vendor_id");
CREATE UNIQUE INDEX "cpe_products_cpe_vendor_id_name_unique" on "cpe_products"(
  "cpe_vendor_id",
  "name"
);
CREATE TABLE IF NOT EXISTS "cpe_versions"(
  "id" integer primary key autoincrement not null,
  "cpe_product_id" integer not null,
  "name" varchar not null,
  foreign key("cpe_product_id") references "cpe_products"("id") on delete NO ACTION on update NO ACTION
);
CREATE INDEX "cpe_version_fk_1485479" on "cpe_versions"("cpe_product_id");
CREATE UNIQUE INDEX "cpe_versions_cpe_product_id_name_unique" on "cpe_versions"(
  "cpe_product_id",
  "name"
);
CREATE TABLE IF NOT EXISTS "security_controls"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "security_controls_name_unique" on "security_controls"(
  "name",
  "deleted_at"
);
CREATE TABLE IF NOT EXISTS "data_processing"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "responsible" text,
  "purpose" text,
  "categories" text,
  "recipients" text,
  "transfert" text,
  "retention" text,
  "controls" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "data_processing_document"(
  "data_processing_id" integer not null,
  "document_id" integer not null,
  foreign key("data_processing_id") references "data_processing"("id") on delete CASCADE on update NO ACTION,
  foreign key("document_id") references "documents"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "data_processing_id_fk_6930583" on "data_processing_document"(
  "data_processing_id"
);
CREATE INDEX "operation_id_fk_4355431" on "data_processing_document"(
  "document_id"
);
CREATE TABLE IF NOT EXISTS "data_processing_process"(
  "data_processing_id" integer not null,
  "process_id" integer not null,
  foreign key("data_processing_id") references "data_processing"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "data_processing_id_fk_5435435" on "data_processing_process"(
  "data_processing_id"
);
CREATE INDEX "process_id_fk_594358" on "data_processing_process"("process_id");
CREATE TABLE IF NOT EXISTS "data_processing_m_application"(
  "data_processing_id" integer not null,
  "m_application_id" integer not null,
  foreign key("data_processing_id") references "data_processing"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "data_processing_id_fk_6948435" on "data_processing_m_application"(
  "data_processing_id"
);
CREATE INDEX "m_applications_id_fk_4384483" on "data_processing_m_application"(
  "m_application_id"
);
CREATE TABLE IF NOT EXISTS "data_processing_information"(
  "data_processing_id" integer not null,
  "information_id" integer not null,
  foreign key("data_processing_id") references "data_processing"("id") on delete CASCADE on update NO ACTION,
  foreign key("information_id") references "information"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "data_processing_id_fk_58305863" on "data_processing_information"(
  "data_processing_id"
);
CREATE INDEX "information_id_fk_4384483" on "data_processing_information"(
  "information_id"
);
CREATE TABLE IF NOT EXISTS "security_control_m_application"(
  "security_control_id" integer not null,
  "m_application_id" integer not null,
  foreign key("security_control_id") references "security_controls"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "m_application_id_fk_5837573" on "security_control_m_application"(
  "m_application_id"
);
CREATE INDEX "security_control_id_fk_5920381" on "security_control_m_application"(
  "security_control_id"
);
CREATE TABLE IF NOT EXISTS "security_control_process"(
  "security_control_id" integer not null,
  "process_id" integer not null,
  foreign key("security_control_id") references "security_controls"("id") on delete CASCADE on update NO ACTION,
  foreign key("process_id") references "processes"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "process_id_fk_5837573" on "security_control_process"(
  "process_id"
);
CREATE INDEX "security_control_id_fk_54354353" on "security_control_process"(
  "security_control_id"
);
CREATE TABLE IF NOT EXISTS "m_application_physical_server"(
  "m_application_id" integer not null,
  "physical_server_id" integer not null,
  foreign key("physical_server_id") references "physical_servers"("id") on delete CASCADE on update NO ACTION,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "m_application_id_fk_5483543" on "m_application_physical_server"(
  "m_application_id"
);
CREATE INDEX "physical_server_id_fk_4543543" on "m_application_physical_server"(
  "physical_server_id"
);
CREATE TABLE IF NOT EXISTS "document_logical_server"(
  "logical_server_id" integer not null,
  "document_id" integer not null,
  foreign key("logical_server_id") references "logical_servers"("id") on delete CASCADE on update NO ACTION,
  foreign key("document_id") references "documents"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "logical_server_id_fk_43832473" on "document_logical_server"(
  "logical_server_id"
);
CREATE INDEX "document_id_fk_1284334" on "document_logical_server"(
  "document_id"
);
CREATE TABLE IF NOT EXISTS "m_application_peripheral"(
  "m_application_id" integer not null,
  "peripheral_id" integer not null,
  foreign key("m_application_id") references "m_applications"("id") on delete CASCADE on update NO ACTION,
  foreign key("peripheral_id") references "peripherals"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "m_application_id_fk_9878654" on "m_application_peripheral"(
  "m_application_id"
);
CREATE INDEX "peripheral_id_fk_6454564" on "m_application_peripheral"(
  "peripheral_id"
);
CREATE TABLE IF NOT EXISTS "clusters"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar,
  "description" text,
  "address_ip" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "cluster_name_unique" on "clusters"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "physical_servers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "responsible" varchar,
  "configuration" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "physical_switch_id" integer,
  "type" varchar,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "address_ip" varchar,
  "cpu" varchar,
  "memory" varchar,
  "disk" varchar,
  "disk_used" varchar,
  "operating_system" varchar,
  "install_date" datetime,
  "update_date" datetime,
  "patching_group" varchar,
  "paching_frequency" integer,
  "next_update" date,
  "cluster_id" integer,
  foreign key("site_id") references sites("id") on delete no action on update no action,
  foreign key("physical_switch_id") references physical_switches("id") on delete no action on update no action,
  foreign key("building_id") references buildings("id") on delete no action on update no action,
  foreign key("bay_id") references bays("id") on delete no action on update no action,
  foreign key("cluster_id") references "clusters"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "bay_fk_1485324" on "physical_servers"("bay_id");
CREATE INDEX "building_fk_1485323" on "physical_servers"("building_id");
CREATE UNIQUE INDEX "physical_servers_name_unique" on "physical_servers"(
  "name",
  "deleted_at"
);
CREATE INDEX "physical_switch_fk_8732342" on "physical_servers"(
  "physical_switch_id"
);
CREATE INDEX "site_fk_1485322" on "physical_servers"("site_id");
CREATE INDEX "cluster_id_fk_5438543" on "physical_servers"("cluster_id");
CREATE TABLE IF NOT EXISTS "routers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "rules" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "ip_addresses" text,
  "type" varchar
);
CREATE UNIQUE INDEX "routers_name_unique" on "routers"("name", "deleted_at");
CREATE TABLE IF NOT EXISTS "relation_values"(
  "relation_id" integer not null,
  "date_price" date,
  "price" numeric,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("relation_id") references "relations"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "relation_id_fk_43243244" on "relation_values"("relation_id");
CREATE TABLE IF NOT EXISTS "document_relation"(
  "relation_id" integer not null,
  "document_id" integer not null,
  foreign key("relation_id") references "relations"("id") on delete CASCADE on update NO ACTION,
  foreign key("document_id") references "documents"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "relation_id_fk_6948334" on "document_relation"("relation_id");
CREATE INDEX "document_id_fk_5492844" on "document_relation"("document_id");
CREATE TABLE IF NOT EXISTS "logical_flows"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "source_ip_range" varchar not null,
  "dest_ip_range" varchar not null,
  "source_port" varchar,
  "dest_port" varchar,
  "protocol" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "router_id" integer,
  "priority" integer,
  "action" varchar,
  "users" varchar,
  "interface" varchar,
  "schedule" varchar,
  foreign key("router_id") references "routers"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "router_id_fk_4382393" on "logical_flows"("router_id");
CREATE TABLE IF NOT EXISTS "physical_router_router"(
  "router_id" integer not null,
  "physical_router_id" integer not null,
  foreign key("physical_router_id") references "physical_routers"("id") on delete CASCADE on update NO ACTION,
  foreign key("router_id") references "routers"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "router_id_fk_958343" on "physical_router_router"("router_id");
CREATE INDEX "physical_router_id_fk_124983" on "physical_router_router"(
  "physical_router_id"
);
CREATE TABLE IF NOT EXISTS "network_switch_physical_switch"(
  "network_switch_id" integer not null,
  "physical_switch_id" integer not null,
  foreign key("network_switch_id") references "network_switches"("id") on delete CASCADE on update NO ACTION,
  foreign key("physical_switch_id") references "physical_switches"("id") on delete CASCADE on update NO ACTION
);
CREATE INDEX "network_switch_id_fk_543323" on "network_switch_physical_switch"(
  "network_switch_id"
);
CREATE INDEX "physical_switch_id_fk_4543143" on "network_switch_physical_switch"(
  "physical_switch_id"
);
CREATE TABLE IF NOT EXISTS "logical_servers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "net_services" varchar,
  "configuration" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "operating_system" varchar,
  "address_ip" varchar,
  "cpu" varchar,
  "memory" varchar,
  "environment" varchar,
  "disk" integer,
  "install_date" datetime,
  "update_date" datetime,
  "disk_used" integer,
  "attributes" varchar,
  "patching_frequency" integer,
  "next_update" date,
  "cluster_id" integer,
  "domain_id" integer,
  "type" varchar,
  foreign key("cluster_id") references clusters("id") on delete cascade on update no action,
  foreign key("domain_id") references "domaine_ads"("id") on delete SET NULL on update NO ACTION
);
CREATE INDEX "cluster_id_fk_5435359" on "logical_servers"("cluster_id");
CREATE UNIQUE INDEX "logical_servers_name_unique" on "logical_servers"(
  "name",
  "deleted_at"
);
CREATE INDEX "domain_id_fk_493844" on "logical_servers"("domain_id");
CREATE TABLE IF NOT EXISTS "m_applications"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "security_need_c" integer,
  "responsible" varchar,
  "type" varchar,
  "technology" varchar,
  "external" varchar,
  "users" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "entity_resp_id" integer,
  "application_block_id" integer,
  "documentation" varchar,
  "security_need_i" integer,
  "security_need_a" integer,
  "security_need_t" integer,
  "version" varchar,
  "functional_referent" varchar,
  "editor" varchar,
  "install_date" datetime,
  "update_date" datetime,
  "rto" integer,
  "rpo" integer,
  "vendor" varchar,
  "product" varchar,
  "attributes" varchar,
  "patching_frequency" integer,
  "next_update" date,
  "icon_id" integer,
  foreign key("entity_resp_id") references entities("id") on delete no action on update no action,
  foreign key("application_block_id") references application_blocks("id") on delete no action on update no action,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE INDEX "application_block_fk_1644592" on "m_applications"(
  "application_block_id"
);
CREATE INDEX "entity_resp_fk_1488612" on "m_applications"("entity_resp_id");
CREATE UNIQUE INDEX "m_applications_name_unique" on "m_applications"(
  "name",
  "deleted_at"
);
CREATE INDEX "document_id_fk_4394343" on "m_applications"("icon_id");
CREATE TABLE IF NOT EXISTS "workstations"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "physical_switch_id" integer,
  "type" varchar,
  "operating_system" varchar,
  "address_ip" varchar,
  "cpu" varchar,
  "memory" varchar,
  "disk" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "icon_id" integer,
  foreign key("site_id") references sites("id") on delete no action on update no action,
  foreign key("physical_switch_id") references physical_switches("id") on delete no action on update no action,
  foreign key("building_id") references buildings("id") on delete no action on update no action,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE INDEX "building_fk_1485333" on "workstations"("building_id");
CREATE INDEX "physical_switch_fk_0938434" on "workstations"(
  "physical_switch_id"
);
CREATE INDEX "site_fk_1485332" on "workstations"("site_id");
CREATE UNIQUE INDEX "workstations_name_unique" on "workstations"(
  "name",
  "deleted_at"
);
CREATE INDEX "document_id_fk_129483" on "workstations"("icon_id");
CREATE TABLE IF NOT EXISTS "peripherals"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar,
  "description" text,
  "responsible" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "site_id" integer,
  "building_id" integer,
  "bay_id" integer,
  "vendor" varchar,
  "product" varchar,
  "version" varchar,
  "address_ip" varchar,
  "domain" varchar,
  "provider_id" integer,
  "icon_id" integer,
  foreign key("provider_id") references entities("id") on delete no action on update no action,
  foreign key("bay_id") references bays("id") on delete no action on update no action,
  foreign key("building_id") references buildings("id") on delete no action on update no action,
  foreign key("site_id") references sites("id") on delete no action on update no action,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE INDEX "bay_fk_1485451" on "peripherals"("bay_id");
CREATE INDEX "building_fk_1485450" on "peripherals"("building_id");
CREATE INDEX "entity_id_fk_4383234" on "peripherals"("provider_id");
CREATE UNIQUE INDEX "peripherals_name_unique" on "peripherals"(
  "name",
  "deleted_at"
);
CREATE INDEX "site_fk_1485449" on "peripherals"("site_id");
CREATE INDEX "document_id_fk_129484" on "peripherals"("icon_id");
CREATE TABLE IF NOT EXISTS "sites"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "icon_id" integer,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE UNIQUE INDEX "sites_name_unique" on "sites"("name", "deleted_at");
CREATE INDEX "document_id_fk_129485" on "sites"("icon_id");
CREATE TABLE IF NOT EXISTS "entities"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "security_level" text,
  "contact_point" text,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "is_external" tinyint(1) not null default('0'),
  "entity_type" varchar,
  "attributes" varchar,
  "reference" varchar,
  "parent_entity_id" integer,
  "icon_id" integer,
  foreign key("parent_entity_id") references entities("id") on delete set null on update no action,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE UNIQUE INDEX "entities_name_unique" on "entities"("name", "deleted_at");
CREATE INDEX "entity_id_fk_4398013" on "entities"("parent_entity_id");
CREATE INDEX "is_external" on "entities"("is_external");
CREATE INDEX "type" on "entities"("entity_type");
CREATE INDEX "document_id_fk_129486" on "entities"("icon_id");
CREATE TABLE IF NOT EXISTS "admin_users"(
  "id" integer primary key autoincrement not null,
  "user_id" varchar not null,
  "firstname" varchar,
  "lastname" varchar,
  "type" varchar,
  "description" text,
  "local" tinyint(1),
  "privileged" tinyint(1),
  "domain_id" integer,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "icon_id" integer,
  foreign key("domain_id") references domaine_ads("id") on delete cascade on update no action,
  foreign key("icon_id") references "documents"("id") on update NO ACTION
);
CREATE INDEX "domain_id_fk_69385935" on "admin_users"("domain_id");
CREATE UNIQUE INDEX "domain_id_user_id_unique" on "admin_users"(
  "domain_id",
  "user_id",
  "deleted_at"
);
CREATE INDEX "document_id_fk_129487" on "admin_users"("icon_id");
CREATE TABLE IF NOT EXISTS "audit_logs"(
  "id" integer primary key autoincrement not null,
  "description" varchar not null,
  "subject_id" integer,
  "subject_type" varchar,
  "user_id" integer,
  "properties" text,
  "host" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_guid_unique" on "users"("guid");

INSERT INTO migrations VALUES(1,'2016_06_01_000001_create_oauth_auth_codes_table',1);
INSERT INTO migrations VALUES(2,'2016_06_01_000002_create_oauth_access_tokens_table',1);
INSERT INTO migrations VALUES(3,'2016_06_01_000003_create_oauth_refresh_tokens_table',1);
INSERT INTO migrations VALUES(4,'2016_06_01_000004_create_oauth_clients_table',1);
INSERT INTO migrations VALUES(5,'2016_06_01_000005_create_oauth_personal_access_clients_table',1);
INSERT INTO migrations VALUES(6,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(7,'2021_05_08_191249_create_activities_table',1);
INSERT INTO migrations VALUES(8,'2021_05_08_191249_create_activity_operation_table',1);
INSERT INTO migrations VALUES(9,'2021_05_08_191249_create_activity_process_table',1);
INSERT INTO migrations VALUES(10,'2021_05_08_191249_create_actor_operation_table',1);
INSERT INTO migrations VALUES(11,'2021_05_08_191249_create_actors_table',1);
INSERT INTO migrations VALUES(12,'2021_05_08_191249_create_annuaires_table',1);
INSERT INTO migrations VALUES(13,'2021_05_08_191249_create_application_blocks_table',1);
INSERT INTO migrations VALUES(14,'2021_05_08_191249_create_application_module_application_service_table',1);
INSERT INTO migrations VALUES(15,'2021_05_08_191249_create_application_modules_table',1);
INSERT INTO migrations VALUES(16,'2021_05_08_191249_create_application_service_m_application_table',1);
INSERT INTO migrations VALUES(17,'2021_05_08_191249_create_application_services_table',1);
INSERT INTO migrations VALUES(18,'2021_05_08_191249_create_audit_logs_table',1);
INSERT INTO migrations VALUES(19,'2021_05_08_191249_create_bay_wifi_terminal_table',1);
INSERT INTO migrations VALUES(20,'2021_05_08_191249_create_bays_table',1);
INSERT INTO migrations VALUES(21,'2021_05_08_191249_create_buildings_table',1);
INSERT INTO migrations VALUES(22,'2021_05_08_191249_create_database_entity_table',1);
INSERT INTO migrations VALUES(23,'2021_05_08_191249_create_database_information_table',1);
INSERT INTO migrations VALUES(24,'2021_05_08_191249_create_database_m_application_table',1);
INSERT INTO migrations VALUES(25,'2021_05_08_191249_create_databases_table',1);
INSERT INTO migrations VALUES(26,'2021_05_08_191249_create_dhcp_servers_table',1);
INSERT INTO migrations VALUES(27,'2021_05_08_191249_create_dnsservers_table',1);
INSERT INTO migrations VALUES(28,'2021_05_08_191249_create_domaine_ad_forest_ad_table',1);
INSERT INTO migrations VALUES(29,'2021_05_08_191249_create_domaine_ads_table',1);
INSERT INTO migrations VALUES(30,'2021_05_08_191249_create_entities_table',1);
INSERT INTO migrations VALUES(31,'2021_05_08_191249_create_entity_m_application_table',1);
INSERT INTO migrations VALUES(32,'2021_05_08_191249_create_entity_process_table',1);
INSERT INTO migrations VALUES(33,'2021_05_08_191249_create_external_connected_entities_table',1);
INSERT INTO migrations VALUES(34,'2021_05_08_191249_create_external_connected_entity_network_table',1);
INSERT INTO migrations VALUES(35,'2021_05_08_191249_create_fluxes_table',1);
INSERT INTO migrations VALUES(36,'2021_05_08_191249_create_forest_ads_table',1);
INSERT INTO migrations VALUES(37,'2021_05_08_191249_create_gateways_table',1);
INSERT INTO migrations VALUES(38,'2021_05_08_191249_create_information_process_table',1);
INSERT INTO migrations VALUES(39,'2021_05_08_191249_create_information_table',1);
INSERT INTO migrations VALUES(40,'2021_05_08_191249_create_lan_man_table',1);
INSERT INTO migrations VALUES(41,'2021_05_08_191249_create_lan_wan_table',1);
INSERT INTO migrations VALUES(42,'2021_05_08_191249_create_lans_table',1);
INSERT INTO migrations VALUES(43,'2021_05_08_191249_create_logical_server_m_application_table',1);
INSERT INTO migrations VALUES(44,'2021_05_08_191249_create_logical_server_physical_server_table',1);
INSERT INTO migrations VALUES(45,'2021_05_08_191249_create_logical_servers_table',1);
INSERT INTO migrations VALUES(46,'2021_05_08_191249_create_m_application_process_table',1);
INSERT INTO migrations VALUES(47,'2021_05_08_191249_create_m_applications_table',1);
INSERT INTO migrations VALUES(48,'2021_05_08_191249_create_macro_processuses_table',1);
INSERT INTO migrations VALUES(49,'2021_05_08_191249_create_man_wan_table',1);
INSERT INTO migrations VALUES(50,'2021_05_08_191249_create_mans_table',1);
INSERT INTO migrations VALUES(51,'2021_05_08_191249_create_media_table',1);
INSERT INTO migrations VALUES(52,'2021_05_08_191249_create_network_subnetword_table',1);
INSERT INTO migrations VALUES(53,'2021_05_08_191249_create_network_switches_table',1);
INSERT INTO migrations VALUES(54,'2021_05_08_191249_create_networks_table',1);
INSERT INTO migrations VALUES(55,'2021_05_08_191249_create_operation_task_table',1);
INSERT INTO migrations VALUES(56,'2021_05_08_191249_create_operations_table',1);
INSERT INTO migrations VALUES(57,'2021_05_08_191249_create_password_resets_table',1);
INSERT INTO migrations VALUES(58,'2021_05_08_191249_create_peripherals_table',1);
INSERT INTO migrations VALUES(59,'2021_05_08_191249_create_permission_role_table',1);
INSERT INTO migrations VALUES(60,'2021_05_08_191249_create_permissions_table',1);
INSERT INTO migrations VALUES(61,'2021_05_08_191249_create_phones_table',1);
INSERT INTO migrations VALUES(62,'2021_05_08_191249_create_physical_router_vlan_table',1);
INSERT INTO migrations VALUES(63,'2021_05_08_191249_create_physical_routers_table',1);
INSERT INTO migrations VALUES(64,'2021_05_08_191249_create_physical_security_devices_table',1);
INSERT INTO migrations VALUES(65,'2021_05_08_191249_create_physical_servers_table',1);
INSERT INTO migrations VALUES(66,'2021_05_08_191249_create_physical_switches_table',1);
INSERT INTO migrations VALUES(67,'2021_05_08_191249_create_processes_table',1);
INSERT INTO migrations VALUES(68,'2021_05_08_191249_create_relations_table',1);
INSERT INTO migrations VALUES(69,'2021_05_08_191249_create_role_user_table',1);
INSERT INTO migrations VALUES(70,'2021_05_08_191249_create_roles_table',1);
INSERT INTO migrations VALUES(71,'2021_05_08_191249_create_routers_table',1);
INSERT INTO migrations VALUES(72,'2021_05_08_191249_create_security_devices_table',1);
INSERT INTO migrations VALUES(73,'2021_05_08_191249_create_sites_table',1);
INSERT INTO migrations VALUES(74,'2021_05_08_191249_create_storage_devices_table',1);
INSERT INTO migrations VALUES(75,'2021_05_08_191249_create_subnetworks_table',1);
INSERT INTO migrations VALUES(76,'2021_05_08_191249_create_tasks_table',1);
INSERT INTO migrations VALUES(77,'2021_05_08_191249_create_users_table',1);
INSERT INTO migrations VALUES(78,'2021_05_08_191249_create_vlans_table',1);
INSERT INTO migrations VALUES(79,'2021_05_08_191249_create_wans_table',1);
INSERT INTO migrations VALUES(80,'2021_05_08_191249_create_wifi_terminals_table',1);
INSERT INTO migrations VALUES(81,'2021_05_08_191249_create_workstations_table',1);
INSERT INTO migrations VALUES(82,'2021_05_08_191249_create_zone_admins_table',1);
INSERT INTO migrations VALUES(83,'2021_05_08_191251_add_foreign_keys_to_activity_operation_table',1);
INSERT INTO migrations VALUES(84,'2021_05_08_191251_add_foreign_keys_to_activity_process_table',1);
INSERT INTO migrations VALUES(85,'2021_05_08_191251_add_foreign_keys_to_actor_operation_table',1);
INSERT INTO migrations VALUES(86,'2021_05_08_191251_add_foreign_keys_to_annuaires_table',1);
INSERT INTO migrations VALUES(87,'2021_05_08_191251_add_foreign_keys_to_application_module_application_service_table',1);
INSERT INTO migrations VALUES(88,'2021_05_08_191251_add_foreign_keys_to_application_service_m_application_table',1);
INSERT INTO migrations VALUES(89,'2021_05_08_191251_add_foreign_keys_to_bay_wifi_terminal_table',1);
INSERT INTO migrations VALUES(90,'2021_05_08_191251_add_foreign_keys_to_bays_table',1);
INSERT INTO migrations VALUES(91,'2021_05_08_191251_add_foreign_keys_to_buildings_table',1);
INSERT INTO migrations VALUES(92,'2021_05_08_191251_add_foreign_keys_to_database_entity_table',1);
INSERT INTO migrations VALUES(93,'2021_05_08_191251_add_foreign_keys_to_database_information_table',1);
INSERT INTO migrations VALUES(94,'2021_05_08_191251_add_foreign_keys_to_database_m_application_table',1);
INSERT INTO migrations VALUES(95,'2021_05_08_191251_add_foreign_keys_to_databases_table',1);
INSERT INTO migrations VALUES(96,'2021_05_08_191251_add_foreign_keys_to_domaine_ad_forest_ad_table',1);
INSERT INTO migrations VALUES(97,'2021_05_08_191251_add_foreign_keys_to_entity_m_application_table',1);
INSERT INTO migrations VALUES(98,'2021_05_08_191251_add_foreign_keys_to_entity_process_table',1);
INSERT INTO migrations VALUES(99,'2021_05_08_191251_add_foreign_keys_to_external_connected_entity_network_table',1);
INSERT INTO migrations VALUES(100,'2021_05_08_191251_add_foreign_keys_to_fluxes_table',1);
INSERT INTO migrations VALUES(101,'2021_05_08_191251_add_foreign_keys_to_forest_ads_table',1);
INSERT INTO migrations VALUES(102,'2021_05_08_191251_add_foreign_keys_to_information_process_table',1);
INSERT INTO migrations VALUES(103,'2021_05_08_191251_add_foreign_keys_to_lan_man_table',1);
INSERT INTO migrations VALUES(104,'2021_05_08_191251_add_foreign_keys_to_lan_wan_table',1);
INSERT INTO migrations VALUES(105,'2021_05_08_191251_add_foreign_keys_to_logical_server_m_application_table',1);
INSERT INTO migrations VALUES(106,'2021_05_08_191251_add_foreign_keys_to_logical_server_physical_server_table',1);
INSERT INTO migrations VALUES(107,'2021_05_08_191251_add_foreign_keys_to_m_application_process_table',1);
INSERT INTO migrations VALUES(108,'2021_05_08_191251_add_foreign_keys_to_m_applications_table',1);
INSERT INTO migrations VALUES(109,'2021_05_08_191251_add_foreign_keys_to_man_wan_table',1);
INSERT INTO migrations VALUES(110,'2021_05_08_191251_add_foreign_keys_to_network_subnetword_table',1);
INSERT INTO migrations VALUES(111,'2021_05_08_191251_add_foreign_keys_to_operation_task_table',1);
INSERT INTO migrations VALUES(112,'2021_05_08_191251_add_foreign_keys_to_peripherals_table',1);
INSERT INTO migrations VALUES(113,'2021_05_08_191251_add_foreign_keys_to_permission_role_table',1);
INSERT INTO migrations VALUES(114,'2021_05_08_191251_add_foreign_keys_to_phones_table',1);
INSERT INTO migrations VALUES(115,'2021_05_08_191251_add_foreign_keys_to_physical_router_vlan_table',1);
INSERT INTO migrations VALUES(116,'2021_05_08_191251_add_foreign_keys_to_physical_routers_table',1);
INSERT INTO migrations VALUES(117,'2021_05_08_191251_add_foreign_keys_to_physical_security_devices_table',1);
INSERT INTO migrations VALUES(118,'2021_05_08_191251_add_foreign_keys_to_physical_servers_table',1);
INSERT INTO migrations VALUES(119,'2021_05_08_191251_add_foreign_keys_to_physical_switches_table',1);
INSERT INTO migrations VALUES(120,'2021_05_08_191251_add_foreign_keys_to_processes_table',1);
INSERT INTO migrations VALUES(121,'2021_05_08_191251_add_foreign_keys_to_relations_table',1);
INSERT INTO migrations VALUES(122,'2021_05_08_191251_add_foreign_keys_to_role_user_table',1);
INSERT INTO migrations VALUES(123,'2021_05_08_191251_add_foreign_keys_to_storage_devices_table',1);
INSERT INTO migrations VALUES(124,'2021_05_08_191251_add_foreign_keys_to_subnetworks_table',1);
INSERT INTO migrations VALUES(125,'2021_05_08_191251_add_foreign_keys_to_wifi_terminals_table',1);
INSERT INTO migrations VALUES(126,'2021_05_08_191251_add_foreign_keys_to_workstations_table',1);
INSERT INTO migrations VALUES(127,'2021_05_13_180642_add_cidt_criteria',1);
INSERT INTO migrations VALUES(128,'2021_05_19_161123_rename_subnetwork',1);
INSERT INTO migrations VALUES(129,'2021_06_22_170555_add_type',1);
INSERT INTO migrations VALUES(130,'2021_07_14_071311_create_certificates_table',1);
INSERT INTO migrations VALUES(131,'2021_08_08_125856_config_right',1);
INSERT INTO migrations VALUES(132,'2021_08_11_201624_certificate_application_link',1);
INSERT INTO migrations VALUES(133,'2021_08_18_171048_network_redesign',1);
INSERT INTO migrations VALUES(134,'2021_08_20_034757_default_gateway',1);
INSERT INTO migrations VALUES(135,'2021_08_28_152910_cleanup',1);
INSERT INTO migrations VALUES(136,'2021_09_19_125048_relation-inportance',1);
INSERT INTO migrations VALUES(137,'2021_09_21_161028_add_router_ip',1);
INSERT INTO migrations VALUES(138,'2021_09_22_114706_add_security_ciat',1);
INSERT INTO migrations VALUES(139,'2021_09_23_192127_rename_descrition',1);
INSERT INTO migrations VALUES(140,'2021_09_28_205405_add_direction_to_flows',1);
INSERT INTO migrations VALUES(141,'2021_10_12_210233_physical_router_name_type',1);
INSERT INTO migrations VALUES(142,'2021_10_19_102610_add_address_ip',1);
INSERT INTO migrations VALUES(143,'2021_11_23_204551_add_app_version',1);
INSERT INTO migrations VALUES(144,'2022_02_08_210603_create_cartographer_m_application_table',1);
INSERT INTO migrations VALUES(145,'2022_02_22_32654_add_cert_status',1);
INSERT INTO migrations VALUES(146,'2022_02_27_162738_add_functional_referent_to_m_application',1);
INSERT INTO migrations VALUES(147,'2022_02_27_163129_add_editor_to_m_application',1);
INSERT INTO migrations VALUES(148,'2022_02_27_192155_add_date_fields_to_m_application',1);
INSERT INTO migrations VALUES(149,'2022_02_28_205630_create_m_application_event_table',1);
INSERT INTO migrations VALUES(150,'2022_05_02_123756_add_update_to_logical_servers',1);
INSERT INTO migrations VALUES(151,'2022_05_18_140331_add_is_external_column_to_entities',1);
INSERT INTO migrations VALUES(152,'2022_05_21_103208_add_type_property_to_entities',1);
INSERT INTO migrations VALUES(153,'2022_06_27_061444_application_workstation',1);
INSERT INTO migrations VALUES(154,'2022_07_28_105153_add_link_operation_process',1);
INSERT INTO migrations VALUES(155,'2022_08_11_165441_add_vpn_fields',1);
INSERT INTO migrations VALUES(156,'2022_09_13_204845_cert_last_notification',1);
INSERT INTO migrations VALUES(157,'2022_12_17_115624_rto_rpo',1);
INSERT INTO migrations VALUES(158,'2023_01_03_205224_database_logical_server',1);
INSERT INTO migrations VALUES(159,'2023_01_08_123726_add_physical_link',1);
INSERT INTO migrations VALUES(160,'2023_01_27_165009_add_flux_nature',1);
INSERT INTO migrations VALUES(161,'2023_01_28_145242_add_logical_devices_link',1);
INSERT INTO migrations VALUES(162,'2023_02_09_164940_gdpr',1);
INSERT INTO migrations VALUES(163,'2023_03_16_123031_create_documents_table',1);
INSERT INTO migrations VALUES(164,'2023_03_22_185812_create_cpe',1);
INSERT INTO migrations VALUES(165,'2023_04_18_123308_add_gdpr_tables',1);
INSERT INTO migrations VALUES(166,'2023_05_29_161406_security_controls_links',1);
INSERT INTO migrations VALUES(167,'2023_06_14_120958_add_physical_address_ip',1);
INSERT INTO migrations VALUES(168,'2023_08_06_100128_add_physicalserver_size',1);
INSERT INTO migrations VALUES(169,'2023_08_07_183714_application_physical_server',1);
INSERT INTO migrations VALUES(170,'2023_09_04_111440_add_application_patching',1);
INSERT INTO migrations VALUES(171,'2023_09_26_074104_iot',1);
INSERT INTO migrations VALUES(172,'2023_10_28_124418_add_cluster',1);
INSERT INTO migrations VALUES(173,'2023_11_30_070804_fix_migration_typo',1);
INSERT INTO migrations VALUES(174,'2024_02_21_085107_application_patching',1);
INSERT INTO migrations VALUES(175,'2024_02_29_134239_patching_attributes',1);
INSERT INTO migrations VALUES(176,'2024_03_14_165211_router_ip_lenght',1);
INSERT INTO migrations VALUES(177,'2024_03_19_195927_contracts',1);
INSERT INTO migrations VALUES(178,'2024_04_06_161307_nomalization',1);
INSERT INTO migrations VALUES(179,'2024_04_08_105719_network_flow',1);
INSERT INTO migrations VALUES(180,'2024_04_14_072101_add_parent_entity',1);
INSERT INTO migrations VALUES(181,'2024_04_28_075916_normalize_process_name',1);
INSERT INTO migrations VALUES(182,'2024_05_09_180526_improve_network_flow',1);
INSERT INTO migrations VALUES(183,'2024_05_15_212326_routers_log_phys',1);
INSERT INTO migrations VALUES(184,'2024_06_03_165236_add_user',1);
INSERT INTO migrations VALUES(185,'2024_06_11_060639_external_connnected_entities_desc',1);
INSERT INTO migrations VALUES(186,'2024_06_18_125723_link_domain_lservers',1);
INSERT INTO migrations VALUES(187,'2024_08_27_200851_normalize_storage_devices',1);
INSERT INTO migrations VALUES(188,'2024_09_22_112404_add_icon',1);
INSERT INTO migrations VALUES(189,'2024_09_24_044657_move_icons_to_docs',1);
INSERT INTO migrations VALUES(190,'2024_09_24_084005_move_icons_to_docs',1);
INSERT INTO migrations VALUES(191,'2024_09_26_160952_building_attributes',1);
INSERT INTO migrations VALUES(192,'2024_10_31_220940_add_vlan_id',1);
INSERT INTO migrations VALUES(193,'2024_11_13_183902_add_type_to_logical_servers',1);
INSERT INTO migrations VALUES(194,'2024_11_19_101048_add_ldap_columns_to_users_table',1);

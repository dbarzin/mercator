<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK constraints on pivot tables (m_application_id → applications)
        DB::statement('ALTER TABLE activity_m_application DROP FOREIGN KEY activity_m_application_m_application_id_foreign');
        DB::statement('ALTER TABLE admin_user_m_application DROP FOREIGN KEY admin_user_m_application_m_application_id_foreign');
        DB::statement('ALTER TABLE application_service_m_application DROP FOREIGN KEY m_application_id_fk_1482585');
        DB::statement('ALTER TABLE cartographer_m_application DROP FOREIGN KEY cartographer_m_application_m_application_id_foreign');
        DB::statement('ALTER TABLE certificate_m_application DROP FOREIGN KEY certificate_m_application_m_application_id_foreign');
        DB::statement('ALTER TABLE container_m_application DROP FOREIGN KEY container_m_application_m_application_id_foreign');
        DB::statement('ALTER TABLE database_m_application DROP FOREIGN KEY m_application_id_fk_1482586');
        DB::statement('ALTER TABLE data_processing_m_application DROP FOREIGN KEY applications_id_fk_0483434');
        DB::statement('ALTER TABLE entity_m_application DROP FOREIGN KEY m_application_id_fk_1488611');
        DB::statement('ALTER TABLE logical_server_m_application DROP FOREIGN KEY m_application_id_fk_1488616');
        DB::statement('ALTER TABLE m_application_peripheral DROP FOREIGN KEY m_application_id_fk_9878654');
        DB::statement('ALTER TABLE m_application_physical_server DROP FOREIGN KEY m_application_id_fk_5483543');
        DB::statement('ALTER TABLE m_application_process DROP FOREIGN KEY m_application_id_fk_1482573');
        DB::statement('ALTER TABLE m_application_security_device DROP FOREIGN KEY m_application_id_fk_41923483');
        DB::statement('ALTER TABLE m_application_workstation DROP FOREIGN KEY m_application_id_fk_1486547');
        DB::statement('ALTER TABLE security_control_m_application DROP FOREIGN KEY m_application_id_fk_304958543');

        // Drop FK constraints on fluxes and m_application_events
        DB::statement('ALTER TABLE fluxes DROP FOREIGN KEY application_source_fk_1485545');
        DB::statement('ALTER TABLE fluxes DROP FOREIGN KEY application_dest_fk_1485549');
        DB::statement('ALTER TABLE m_application_events DROP FOREIGN KEY m_application_events_m_application_id_foreign');

        // Rename main table
        DB::statement('RENAME TABLE m_applications TO applications');

        // Rename pivot tables: m_application_id → application_id, then rename table
        DB::statement('ALTER TABLE activity_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE activity_m_application TO activity_application');

        DB::statement('ALTER TABLE admin_user_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE admin_user_m_application TO admin_user_application');

        DB::statement('ALTER TABLE application_service_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE application_service_m_application TO application_application_service');

        DB::statement('ALTER TABLE cartographer_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE cartographer_m_application TO application_cartographer');

        DB::statement('ALTER TABLE certificate_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE certificate_m_application TO application_certificate');

        DB::statement('ALTER TABLE container_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE container_m_application TO application_container');

        DB::statement('ALTER TABLE database_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE database_m_application TO application_database');

        DB::statement('ALTER TABLE data_processing_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE data_processing_m_application TO application_data_processing');

        DB::statement('ALTER TABLE entity_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE entity_m_application TO application_entity');

        DB::statement('ALTER TABLE logical_server_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE logical_server_m_application TO application_logical_server');

        DB::statement('ALTER TABLE m_application_peripheral RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE m_application_peripheral TO application_peripheral');

        DB::statement('ALTER TABLE m_application_physical_server RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE m_application_physical_server TO application_physical_server');

        DB::statement('ALTER TABLE m_application_process RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE m_application_process TO application_process');

        DB::statement('ALTER TABLE m_application_security_device RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE m_application_security_device TO application_security_device');

        DB::statement('ALTER TABLE m_application_workstation RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE m_application_workstation TO application_workstation');

        DB::statement('ALTER TABLE security_control_m_application RENAME COLUMN m_application_id TO application_id');
        DB::statement('RENAME TABLE security_control_m_application TO application_security_control');

        // Re-add FK constraints on pivot tables → applications
        DB::statement('ALTER TABLE activity_application ADD CONSTRAINT activity_application_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE admin_user_application ADD CONSTRAINT admin_user_application_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_application_service ADD CONSTRAINT application_application_service_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_cartographer ADD CONSTRAINT application_cartographer_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_certificate ADD CONSTRAINT application_certificate_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_container ADD CONSTRAINT application_container_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_database ADD CONSTRAINT application_database_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_data_processing ADD CONSTRAINT application_data_processing_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_entity ADD CONSTRAINT application_entity_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_logical_server ADD CONSTRAINT application_logical_server_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_peripheral ADD CONSTRAINT application_peripheral_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_physical_server ADD CONSTRAINT application_physical_server_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_process ADD CONSTRAINT application_process_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_security_device ADD CONSTRAINT application_security_device_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_workstation ADD CONSTRAINT application_workstation_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE application_security_control ADD CONSTRAINT application_security_control_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');

        // Re-add FK constraints on fluxes and m_application_events → applications
        DB::statement('ALTER TABLE fluxes ADD CONSTRAINT application_source_fk_1485545 FOREIGN KEY (application_source_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE fluxes ADD CONSTRAINT application_dest_fk_1485549 FOREIGN KEY (application_dest_id) REFERENCES applications(id)');
        DB::statement('ALTER TABLE m_application_events ADD CONSTRAINT m_application_events_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES applications(id)');
    }

    public function down(): void
    {
        // Drop FK constraints added in up()
        DB::statement('ALTER TABLE activity_application DROP FOREIGN KEY activity_application_application_id_foreign');
        DB::statement('ALTER TABLE admin_user_application DROP FOREIGN KEY admin_user_application_application_id_foreign');
        DB::statement('ALTER TABLE application_application_service DROP FOREIGN KEY application_application_service_application_id_foreign');
        DB::statement('ALTER TABLE application_cartographer DROP FOREIGN KEY application_cartographer_application_id_foreign');
        DB::statement('ALTER TABLE application_certificate DROP FOREIGN KEY application_certificate_application_id_foreign');
        DB::statement('ALTER TABLE application_container DROP FOREIGN KEY application_container_application_id_foreign');
        DB::statement('ALTER TABLE application_database DROP FOREIGN KEY application_database_application_id_foreign');
        DB::statement('ALTER TABLE application_data_processing DROP FOREIGN KEY application_data_processing_application_id_foreign');
        DB::statement('ALTER TABLE application_entity DROP FOREIGN KEY application_entity_application_id_foreign');
        DB::statement('ALTER TABLE application_logical_server DROP FOREIGN KEY application_logical_server_application_id_foreign');
        DB::statement('ALTER TABLE application_peripheral DROP FOREIGN KEY application_peripheral_application_id_foreign');
        DB::statement('ALTER TABLE application_physical_server DROP FOREIGN KEY application_physical_server_application_id_foreign');
        DB::statement('ALTER TABLE application_process DROP FOREIGN KEY application_process_application_id_foreign');
        DB::statement('ALTER TABLE application_security_device DROP FOREIGN KEY application_security_device_application_id_foreign');
        DB::statement('ALTER TABLE application_workstation DROP FOREIGN KEY application_workstation_application_id_foreign');
        DB::statement('ALTER TABLE application_security_control DROP FOREIGN KEY application_security_control_application_id_foreign');
        DB::statement('ALTER TABLE fluxes DROP FOREIGN KEY application_source_fk_1485545');
        DB::statement('ALTER TABLE fluxes DROP FOREIGN KEY application_dest_fk_1485549');
        DB::statement('ALTER TABLE m_application_events DROP FOREIGN KEY m_application_events_m_application_id_foreign');

        // Restore main table name
        DB::statement('RENAME TABLE applications TO m_applications');

        // Restore pivot tables: rename table back, rename application_id → m_application_id
        DB::statement('RENAME TABLE activity_application TO activity_m_application');
        DB::statement('ALTER TABLE activity_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE admin_user_application TO admin_user_m_application');
        DB::statement('ALTER TABLE admin_user_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_application_service TO application_service_m_application');
        DB::statement('ALTER TABLE application_service_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_cartographer TO cartographer_m_application');
        DB::statement('ALTER TABLE cartographer_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_certificate TO certificate_m_application');
        DB::statement('ALTER TABLE certificate_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_container TO container_m_application');
        DB::statement('ALTER TABLE container_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_database TO database_m_application');
        DB::statement('ALTER TABLE database_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_data_processing TO data_processing_m_application');
        DB::statement('ALTER TABLE data_processing_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_entity TO entity_m_application');
        DB::statement('ALTER TABLE entity_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_logical_server TO logical_server_m_application');
        DB::statement('ALTER TABLE logical_server_m_application RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_peripheral TO m_application_peripheral');
        DB::statement('ALTER TABLE m_application_peripheral RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_physical_server TO m_application_physical_server');
        DB::statement('ALTER TABLE m_application_physical_server RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_process TO m_application_process');
        DB::statement('ALTER TABLE m_application_process RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_security_device TO m_application_security_device');
        DB::statement('ALTER TABLE m_application_security_device RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_workstation TO m_application_workstation');
        DB::statement('ALTER TABLE m_application_workstation RENAME COLUMN application_id TO m_application_id');

        DB::statement('RENAME TABLE application_security_control TO security_control_m_application');
        DB::statement('ALTER TABLE security_control_m_application RENAME COLUMN application_id TO m_application_id');

        // Restore original FK constraints
        DB::statement('ALTER TABLE activity_m_application ADD CONSTRAINT activity_m_application_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE admin_user_m_application ADD CONSTRAINT admin_user_m_application_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE application_service_m_application ADD CONSTRAINT m_application_id_fk_1482585 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE cartographer_m_application ADD CONSTRAINT cartographer_m_application_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE certificate_m_application ADD CONSTRAINT certificate_m_application_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE container_m_application ADD CONSTRAINT container_m_application_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE database_m_application ADD CONSTRAINT m_application_id_fk_1482586 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE data_processing_m_application ADD CONSTRAINT applications_id_fk_0483434 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE entity_m_application ADD CONSTRAINT m_application_id_fk_1488611 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE logical_server_m_application ADD CONSTRAINT m_application_id_fk_1488616 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_peripheral ADD CONSTRAINT m_application_id_fk_9878654 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_physical_server ADD CONSTRAINT m_application_id_fk_5483543 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_process ADD CONSTRAINT m_application_id_fk_1482573 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_security_device ADD CONSTRAINT m_application_id_fk_41923483 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_workstation ADD CONSTRAINT m_application_id_fk_1486547 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE security_control_m_application ADD CONSTRAINT m_application_id_fk_304958543 FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE fluxes ADD CONSTRAINT application_source_fk_1485545 FOREIGN KEY (application_source_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE fluxes ADD CONSTRAINT application_dest_fk_1485549 FOREIGN KEY (application_dest_id) REFERENCES m_applications(id)');
        DB::statement('ALTER TABLE m_application_events ADD CONSTRAINT m_application_events_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES m_applications(id)');
    }
};

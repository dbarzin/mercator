<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE application_events DROP FOREIGN KEY application_events_m_application_id_foreign');
        DB::statement('ALTER TABLE application_events RENAME COLUMN m_application_id TO application_id');
        DB::statement('ALTER TABLE application_events ADD CONSTRAINT application_events_application_id_foreign FOREIGN KEY (application_id) REFERENCES applications(id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE application_events DROP FOREIGN KEY application_events_application_id_foreign');
        DB::statement('ALTER TABLE application_events RENAME COLUMN application_id TO m_application_id');
        DB::statement('ALTER TABLE application_events ADD CONSTRAINT application_events_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES applications(id)');
    }
};

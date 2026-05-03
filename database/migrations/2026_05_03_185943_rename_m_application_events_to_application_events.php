<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE m_application_events DROP FOREIGN KEY m_application_events_m_application_id_foreign');
        DB::statement('RENAME TABLE m_application_events TO application_events');
        DB::statement('ALTER TABLE application_events ADD CONSTRAINT application_events_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES applications(id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE application_events DROP FOREIGN KEY application_events_m_application_id_foreign');
        DB::statement('RENAME TABLE application_events TO m_application_events');
        DB::statement('ALTER TABLE m_application_events ADD CONSTRAINT m_application_events_m_application_id_foreign FOREIGN KEY (m_application_id) REFERENCES applications(id)');
    }
};

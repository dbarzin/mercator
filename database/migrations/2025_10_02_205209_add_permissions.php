<?php

use Mercator\Core\Models\Permission;
use Mercator\Core\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // if not initial migration -> add permissions
        if (Permission::count() > 0) {
            // create new permissions
            Permission::insert(
                [
                    [
                        'id' => 306,
                        'title' => 'explore_access',
                    ],
                    [
                        'id' => 307,
                        'title' => 'tools_access',
                    ],
                    [
                        'id' => 308,
                        'title' => 'reports_access',
                    ],
                ]);
            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([306, 307, 308], false);
            // User
            Role::findOrFail(2)->permissions()->sync([306, 307, 308], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([306, 307, 308], false);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete('delete from permissions where id=306;');
        DB::delete('delete from permissions where id=307;');
        DB::delete('delete from permissions where id=308;');
    }
};

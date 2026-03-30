<?php

use Illuminate\Database\Migrations\Migration;
use Mercator\Core\Models\Permission;
use Mercator\Core\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // if not initial migration -> add permissions
        if (Permission::query()->count() > 0) {
            // create new permissions
            Permission::query()->insert(
                [
                    [
                        'id' => 310,
                        'title' => 'document_create',
                    ],
                    [
                        'id' => 311,
                        'title' => 'document_edit',
                    ],
                    [
                        'id' => 312,
                        'title' => 'document_show',
                    ],
                    [
                        'id' => 313,
                        'title' => 'document_delete',
                    ],
                    [
                        'id' => 314,
                        'title' => 'document_access',
                    ],
                ]);
            // Add permissions in roles :
            // Admin
            Role::query()->findOrFail(1)->permissions()->sync([310, 311, 312, 313, 314], false);
            // User
            Role::query()->findOrFail(2)->permissions()->sync([310, 311, 312, 313, 314], false);
            // Auditor
            Role::query()->findOrFail(3)->permissions()->sync([312, 314], false);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete('delete from permissions where id=310;');
        DB::delete('delete from permissions where id=311;');
        DB::delete('delete from permissions where id=312;');
        DB::delete('delete from permissions where id=313;');
        DB::delete('delete from permissions where id=314;');
    }
};

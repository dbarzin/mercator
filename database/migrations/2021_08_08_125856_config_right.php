<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class ConfigRight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if not initial migration -> add permissions
        if (Permission::count() > 0) {

            $permissions = [
                [
                    'id' => '262',
                    'title' => 'configure',
                ],
            ];
            Permission::insert($permissions);

            $admin_permissions = Permission::all();

            // Add permissions in role admin:
            Role::findOrFail(1)->permissions()->sync([262], false);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('delete from permissions where id=262;');
    }
}

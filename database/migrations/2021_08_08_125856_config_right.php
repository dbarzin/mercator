<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        if (Permission::count()>0) {

            $permissions = [
                [
                    'id'    => '262',
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

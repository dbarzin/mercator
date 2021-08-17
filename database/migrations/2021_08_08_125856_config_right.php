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
        $permissions = [
            [
                'id'    => '262',
                'title' => 'configure',
            ],
        ];

        Permission::insert($permissions);

        $admin_permissions = Permission::all();
        // find administrator
        $admin = Role::find(1);
        // admin might not exists already at initial creation
        if ($admin!=null)            
            $admin->permissions()->sync($admin_permissions->pluck('id'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('delete from permissions where id=262;');

        $admin_permissions = Permission::all();
        $admin=Role::find(1);
        if($admin!=null)
            $admin->permissions()->sync($admin_permissions->pluck('id'));        
    }
}

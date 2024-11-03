<?php

namespace Database\Seeders;

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $all_permissions = Permission::all();

        // Administrator
        $admin_permissions = $all_permissions->filter(function ($permission) {
                return
                    // deprecated
                    !str_starts_with($permission->title,"dnsserver_") &&
                    // deprecated
                    !str_starts_with($permission->title,"dhcp_server_")
                    ;
        });
        Role::findOrFail(1)->permissions()->sync($admin_permissions);

        // User
        $user_permissions = $all_permissions->filter(function ($permission) {
            return  substr($permission->title, 0, 5) != 'user_' &&
                    substr($permission->title, 0, 5) != 'role_' &&
                    substr($permission->title, 0, 11) != 'permission_' &&
                    ($permission->title != "profile_password_edit") &&
                    // deprecated
                    !str_starts_with($permission->title,"dnsserver_") &&
                    // deprecated
                    !str_starts_with($permission->title,"dhcp_server_")
                    ;
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);

        // Auditor
        $auditor_permissions = $all_permissions->filter(function ($permission) {
            return  substr($permission->title, 0, 5) != 'user_' &&
                    substr($permission->title, 0, 5) != 'role_' &&
                    substr($permission->title, 0, 11) != 'permission_' &&
                    (
                        substr($permission->title, strlen($permission->title)-5, strlen($permission->title)) == '_show' ||
                        substr($permission->title, strlen($permission->title)-7, strlen($permission->title)) == '_access'
                    ) &&
                    ($permission->title != "profile_password_edit") &&
                    // deprecated
                    !str_starts_with($permission->title,"dnsserver_") &&
                    // deprecated
                    !str_starts_with($permission->title,"dhcp_server_");
        });
        Role::findOrFail(3)->permissions()->sync($auditor_permissions);

        // Cartographer
        $cartographer_permissions = $all_permissions->filter(function ($permission) {
           return (
                str_starts_with($permission->title, 'papplication_') ||
                str_starts_with($permission->title, 'm_application_') ||
                str_starts_with($permission->title, 'application_service_') ||
                str_starts_with($permission->title, 'database_') ||
                str_starts_with($permission->title, 'flux_') ||
                str_starts_with($permission->title, 'application_block_') ||
                str_starts_with($permission->title, 'application_module_')
                ) &&
                ($permission->title != "profile_password_edit")&&
                // deprecated
                !str_starts_with($permission->title,"dnsserver_") &&
                // deprecated
                !str_starts_with($permission->title,"dhcp_server_");
        });
        Role::findOrFail(4)->permissions()->sync($cartographer_permissions);
    }
}

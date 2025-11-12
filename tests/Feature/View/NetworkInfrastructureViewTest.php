<?php

use App\Models\User;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed base permissions/roles and users as in other feature tests
    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);

    // Login as an admin (id=1 seeded by UsersTableSeeder)
    $this->user = User::query()->find(1);
    $this->actingAs($this->user);
});

describe('Network Infrastructure View', function () {
    test('can display network-infrastructure view page', function () {
        $response = $this->get(route('admin.report.view.network-infrastructure'));

        $response->assertOk();
        $response->assertViewIs('admin.reports.network_infrastructure');
        $response->assertViewHasAll([
            'all_sites',
            'sites',
            'all_buildings',
            'buildings',
            'bays',
            'physicalServers',
            'workstations',
            'storageDevices',
            'peripherals',
            'phones',
            'physicalSwitches',
            'physicalRouters',
            'wifiTerminals',
            'physicalSecurityDevices',
            'physicalLinks',
        ]);
    });

    test('denies access without permission', function () {
        // New user without the reports_access permission
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.report.view.network-infrastructure'));

        $response->assertForbidden();
    });
});

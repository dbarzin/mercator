
<?php

use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mercator\Core\Models\Activity;
use Mercator\Core\Models\AuditLog;
use Mercator\Core\Models\LogicalServer;
use Mercator\Core\Models\MApplication;
use Mercator\Core\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);

    $this->user = User::query()->where('login','admin@admin.com')->first();
    $this->actingAs($this->user);

});

describe('index', function () {

    test('can display logs index page', function () {

        // create some objets
        Activity::factory()->count(3)->create();
        MApplication::factory()->count(3)->create();
        \Mercator\Core\Models\Process::factory()->count(3)->create();
        \Mercator\Core\Models\PhysicalRouter::factory()->count(3)->create();

        // Test audit log page
        $response = $this->get(route('admin.audit-logs.index'));

        $response->assertOk();
        $response->assertViewIs('admin.auditLogs.index');
        $response->assertViewHas('logs');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.audit-logs.index'));

        $response->assertForbidden();
    });

});

describe('show', function () {

    test('can display logs index page', function () {

        // create an object
        $logicalServer = LogicalServer::factory()->create();
        $id = AuditLog::query()->orderBy('id','desc')->first()->id;

        // Test audit log page
        $response = $this->get(route('admin.audit-logs.show', $id));

        $response->assertOk();
        $response->assertViewIs('admin.auditLogs.show')
            ->assertSee('LogicalServer')
            ->assertSee($logicalServer->id)
            ->assertSee($logicalServer->name);
    });

});

describe('history', function () {

    test('can display history page', function () {

        // create an object
        $activity = Activity::factory()->create();

        // Test audit log page
        $response = $this->get(route('admin.audit-logs.history', [
                'type'=> 'Mercator\\Core\\Models\\Activity',
                'id' => $activity->id
                ]
        ));

        $response->assertOk();
        $response->assertViewIs('admin.auditLogs.history')
            ->assertSee($activity->name);
    });

});


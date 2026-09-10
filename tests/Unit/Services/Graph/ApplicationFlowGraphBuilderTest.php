<?php

use App\Models\Application;
use App\Models\ApplicationFlow;
use App\Models\Database;
use App\Models\User;
use App\Services\Graph\ApplicationFlowGraphBuilder;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);

    $this->actingAs(User::query()->where('login', 'admin@admin.com')->first());
});

test('buildDot draws a labeled, bidirectional edge between an application and a database', function () {
    $application = Application::factory()->create();
    $database = Database::factory()->create();
    $flow = ApplicationFlow::factory()->create([
        'type' => 'sync',
        'bidirectional' => true,
        'application_source_id' => $application->id,
        'database_dest_id' => $database->id,
    ]);

    $builder = new ApplicationFlowGraphBuilder;
    $dot = $builder->buildDot(
        Application::all(),
        new Collection,
        new Collection,
        Database::all(),
        ApplicationFlow::all(),
    );

    expect($dot)
        ->toContain('A'.$application->id.' -> DB'.$database->id)
        ->toContain('label="sync"')
        ->toContain('dir="both"')
        ->toContain('#'.$flow->getUID());
});

test('buildDot skips flows with no resolvable source or destination', function () {
    ApplicationFlow::factory()->create();

    $builder = new ApplicationFlowGraphBuilder;
    $dot = $builder->buildDot(new Collection, new Collection, new Collection, new Collection, ApplicationFlow::all());

    expect($dot)->not->toContain('->');
});

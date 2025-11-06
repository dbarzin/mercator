<?php

use App\Models\MApplication;
use App\Models\User;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);

    $this->user = User::query()->find(1);
    $this->actingAs($this->user);

});

describe('index', function () {
    test('can display applications index page', function () {
        MApplication::factory()->count(3)->create();

        $response = $this->get(route('admin.applications.index'));

        $response->assertOk();
        $response->assertViewIs('admin.applications.index');
        $response->assertViewHas('applications');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.applications.index'));

        $response->assertForbidden();
    });

});

describe('create', function () {
    test('can display create form', function () {
        $response = $this->get(route('admin.applications.create'));

        $response->assertOk();
        $response->assertViewIs('admin.applications.create');
        $response->assertViewHas([
            'activities',
            'services',
            'databases',
        ]);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.applications.create'));

        $response->assertForbidden();
    });

});

describe('show', function () {

        test('can display object', function () {
            $name =  fake()->word();
            $container = MApplication::factory()->create(['name' => $name]);

            $response = $this->get(route('admin.applications.show', $container->id));

            $response->assertOk();
            $response->assertViewIs('admin.applications.show');
            $response->assertSee($name);
        });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $name =  fake()->word();
        $container = MApplication::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.applications.show', $container->id));

        $response->assertForbidden();
    });

});

describe('edit', function () {
    test('can display edit form', function () {
        $name =  fake()->word();
        $container = MApplication::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.applications.edit', $container));

        $response->assertOk();
        $response->assertViewIs('admin.applications.edit');
        $response->assertViewHas('application');
        $response->assertSee($name);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $container = MApplication::factory()->create();

        $response = $this->get(route('admin.applications.edit', $container));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update MApplication', function () {
        $name =  fake()->word();
        $container = MApplication::factory()->create(['name' => $name]);

        $data = [
            'name' => 'Updated Name',
            'description' => fake()->sentence(),
        ];

        $response = $this->put(route('admin.applications.update', $container), $data);

        $response->assertRedirect(route('admin.applications.index'));
        $this->assertDatabaseHas('m_applications', ['name' => 'Updated Name']);
    });
});

describe('destroy', function () {
    test('can delete activity', function () {
        $container = MApplication::factory()->create();

        $response = $this->delete(route('admin.applications.destroy', $container->id));
        $response->assertRedirect(route('admin.applications.index'));

        $this->assertSoftDeleted('m_applications', ['id' => $container->id]);

        $container->refresh();
        expect($container->deleted_at)->not->toBeNull()
            ->and($container->trashed())->toBeTrue();

    });

test('denies access without permission', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $container = MApplication::factory()->create();

    $response = $this->delete(route('admin.applications.destroy', $container));

    $response->assertForbidden();
    });
});

describe('massDestroy', function () {
    test('can delete multiple applications', function () {
        $application = MApplication::factory()->count(3)->create();
        $ids = $application->pluck('id')->toArray();

        $response = $this->delete(route('admin.applications.massDestroy'), ['ids' => $ids]);
        $response->assertNoContent();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('m_applications', ['id' => $id]);
        }
    });

    test('returns no content status', function () {
        $application = MApplication::factory()->create();

        $response = $this->delete(route('admin.applications.massDestroy'), [
            'ids' => [$application->id],
        ]);

        $response->assertStatus(204);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $application = MApplication::factory()->create();

        $response = $this->delete(route('admin.applications.massDestroy'), [
            'ids' => [$application->id],
        ]);

        $response->assertForbidden();
    });


});

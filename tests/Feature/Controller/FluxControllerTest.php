<?php

use App\Models\Flux;
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
    test('can display fluxes index page', function () {
        Flux::factory()->count(3)->create();

        $response = $this->get(route('admin.fluxes.index'));

        $response->assertOk();
        $response->assertViewIs('admin.fluxes.index');
        $response->assertViewHas('fluxes');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.fluxes.index'));

        $response->assertForbidden();
    });

});

describe('create', function () {
    test('can display create form', function () {
        $response = $this->get(route('admin.fluxes.create'));

        $response->assertOk();
        $response->assertViewIs('admin.fluxes.create');
        $response->assertViewHas(['items', 'nature_list', 'attributes_list']);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.fluxes.create'));

        $response->assertForbidden();
    });

});

describe('show', function () {

    test('can display object', function () {
        $name = fake()->word();
        $flux = Flux::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.fluxes.show', $flux->id));

        $response->assertOk();
        $response->assertViewIs('admin.fluxes.show');
        $response->assertSee($name);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $name = fake()->word();
        $flux = Flux::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.fluxes.show', $flux->id));

        $response->assertForbidden();
    });

});

describe('edit', function () {
    test('can display edit form', function () {
        $name = fake()->word();
        $flux = Flux::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.fluxes.edit', $flux));

        $response->assertOk();
        $response->assertViewIs('admin.fluxes.edit');
        $response->assertViewHas(['items', 'nature_list', 'attributes_list', 'flux']);
        $response->assertSee($name);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $flux = Flux::factory()->create();

        $response = $this->get(route('admin.fluxes.edit', $flux));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update flux', function () {
        $name = fake()->word();
        $flux = Flux::factory()->create(['name' => $name]);

        $data = [
            'name' => 'Updated Name',
            'description' => fake()->sentence(),
        ];

        $response = $this->put(route('admin.fluxes.update', $flux), $data);

        $response->assertRedirect(route('admin.fluxes.index'));
        $this->assertDatabaseHas('fluxes', ['name' => 'Updated Name']);
    });
});

describe('destroy', function () {
    test('can delete flux', function () {
        $flux = Flux::factory()->create();

        $response = $this->delete(route('admin.fluxes.destroy', $flux->id));
        $response->assertRedirect(route('admin.fluxes.index'));

        $this->assertSoftDeleted('fluxes', ['id' => $flux->id]);

        $flux->refresh();
        expect($flux->deleted_at)->not->toBeNull()
            ->and($flux->trashed())->toBeTrue();

    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $flux = Flux::factory()->create();

        $response = $this->delete(route('admin.fluxes.destroy', $flux));

        $response->assertForbidden();
    });
});

describe('massDestroy', function () {
    test('can delete multiple fluxes', function () {
        $fluxes = Flux::factory()->count(3)->create();
        $ids = $fluxes->pluck('id')->toArray();

        $response = $this->delete(route('admin.fluxes.massDestroy'), ['ids' => $ids]);
        $response->assertNoContent();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('fluxes', ['id' => $id]);
        }
    });

    test('returns no content status', function () {
        $flux = Flux::factory()->create();

        $response = $this->delete(route('admin.fluxes.massDestroy'), [
            'ids' => [$flux->id],
        ]);

        $response->assertStatus(204);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $flux = Flux::factory()->create();

        $response = $this->delete(route('admin.fluxes.massDestroy'), [
            'ids' => [$flux->id],
        ]);

        $response->assertForbidden();
    });

});

<?php

use Mercator\Core\Models\DomaineAd;
use Mercator\Core\Models\User;
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

    $this->user = User::query()->where('login','admin@admin.com')->first();
    $this->actingAs($this->user);

});

describe('index', function () {
    test('can display domain-ad index page', function () {
        DomaineAd::factory()->count(3)->create();

        $response = $this->get(route('admin.domaine-ads.index'));

        $response->assertOk();
        $response->assertViewIs('admin.domaineAds.index');
        $response->assertViewHas('domaineAds');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.domaine-ads.index'));

        $response->assertForbidden();
    });

});

describe('create', function () {
    test('can display create form', function () {
        $response = $this->get(route('admin.domaine-ads.create'));

        $response->assertOk();
        $response->assertViewIs('admin.domaineAds.create');
        $response->assertViewHas(['logicalServers']);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.domaine-ads.create'));

        $response->assertForbidden();
    });

});

describe('show', function () {

    test('can display object', function () {
        $name = fake()->word();
        $container = DomaineAd::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.domaine-ads.show', $container->id));

        $response->assertOk();
        $response->assertViewIs('admin.domaineAds.show');
        $response->assertSee($name);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $name = fake()->word();
        $container = DomaineAd::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.domaine-ads.show', $container->id));

        $response->assertForbidden();
    });

});

describe('edit', function () {
    test('can display edit form', function () {
        $name = fake()->word();
        $container = DomaineAd::factory()->create(['name' => $name]);

        $response = $this->get(route('admin.domaine-ads.edit', $container));

        $response->assertOk();
        $response->assertViewIs('admin.domaineAds.edit');
        $response->assertViewHas('logicalServers');
        $response->assertSee($name);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $container = DomaineAd::factory()->create();

        $response = $this->get(route('admin.domaine-ads.edit', $container));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update activity', function () {
        $name = fake()->word();
        $container = DomaineAd::factory()->create(['name' => $name]);

        $data = [
            'name' => 'Updated Name',
            'description' => fake()->sentence(),
        ];

        $response = $this->put(route('admin.domaine-ads.update', $container), $data);

        $response->assertRedirect(route('admin.domaine-ads.index'));
        $this->assertDatabaseHas('domaine_ads', ['name' => 'Updated Name']);
    });
});

describe('destroy', function () {
    test('can delete activity', function () {
        $container = DomaineAd::factory()->create();

        $response = $this->delete(route('admin.domaine-ads.destroy', $container->id));
        $response->assertRedirect(route('admin.domaine-ads.index'));

        $this->assertSoftDeleted('domaine_ads', ['id' => $container->id]);

        $container->refresh();
        expect($container->deleted_at)->not->toBeNull()
            ->and($container->trashed())->toBeTrue();

    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $container = DomaineAd::factory()->create();

        $response = $this->delete(route('admin.domaine-ads.destroy', $container));

        $response->assertForbidden();
    });
});

describe('massDestroy', function () {
    test('can delete multiple domain-ad', function () {
        $domaine = DomaineAd::factory()->count(3)->create();
        $ids = $domaine->pluck('id')->toArray();

        $response = $this->delete(route('admin.domaine-ads.massDestroy'), ['ids' => $ids]);
        $response->assertNoContent();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('domaine_ads', ['id' => $id]);
        }
    });

    test('returns no content status', function () {
        $domaine = DomaineAd::factory()->create();

        $response = $this->delete(route('admin.domaine-ads.massDestroy'), [
            'ids' => [$domaine->id],
        ]);

        $response->assertStatus(204);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $domaine = DomaineAd::factory()->create();

        $response = $this->delete(route('admin.domaine-ads.massDestroy'), [
            'ids' => [$domaine->id],
        ]);

        $response->assertForbidden();
    });

});

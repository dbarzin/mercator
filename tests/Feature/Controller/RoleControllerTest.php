
<?php

use App\Models\Role;
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
    test('can display roles index page', function () {
        Role::factory()->count(3)->create();

        $response = $this->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.index');
        $response->assertViewHas('roles');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.roles.index'));

        $response->assertForbidden();
    });

});

describe('create', function () {
    test('can display create form', function () {
        $response = $this->get(route('admin.roles.create'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.create');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.roles.create'));

        $response->assertForbidden();
    });

});

describe('show', function () {

    test('can display object', function () {
        $title = fake()->word();
        $Role = Role::factory()->create(['title' => $title]);

        $response = $this->get(route('admin.roles.show', $Role->id));

        $response->assertOk();
        $response->assertViewIs('admin.roles.show');
        $response->assertSee($title);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $title = fake()->word();
        $Role = Role::factory()->create(['title' => $title]);

        $response = $this->get(route('admin.roles.show', $Role->id));

        $response->assertForbidden();
    });

});

describe('edit', function () {
    test('can display edit form', function () {
        $title = fake()->word();
        $Role = Role::factory()->create(['title' => $title]);

        $response = $this->get(route('admin.roles.edit', $Role));

        $response->assertOk();
        $response->assertViewIs('admin.roles.edit');
        $response->assertViewHas('role');
        $response->assertSee($title);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $Role = Role::factory()->create();

        $response = $this->get(route('admin.roles.edit', $Role));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update Role', function () {
        $title = fake()->word();
        $Role = Role::factory()->create(['title' => $title]);

        $data = [
            'title' => 'Updated Name',
            'description' => fake()->sentence(),
        ];

        $response = $this->put(route('admin.roles.update', $Role), $data);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['title' => 'Updated Name']);
    });
});

describe('destroy', function () {
    test('can delete Role', function () {
        $Role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.destroy', $Role->id));
        $response->assertRedirect(route('admin.roles.index'));

        $this->assertSoftDeleted('roles', ['id' => $Role->id]);

        $Role->refresh();
        expect($Role->deleted_at)->not->toBeNull()
            ->and($Role->trashed())->toBeTrue();

    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $Role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.destroy', $Role));

        $response->assertForbidden();
    });
});

describe('massDestroy', function () {
    test('can delete multiple roles', function () {
        $roles = Role::factory()->count(3)->create();
        $ids = $roles->pluck('id')->toArray();

        $response = $this->delete(route('admin.roles.massDestroy'), ['ids' => $ids]);
        $response->assertNoContent();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('roles', ['id' => $id]);
        }
    });

    test('returns no content status', function () {
        $Role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.massDestroy'), [
            'ids' => [$Role->id],
        ]);

        $response->assertStatus(204);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $Role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.massDestroy'), [
            'ids' => [$Role->id],
        ]);

        $response->assertForbidden();
    });

});

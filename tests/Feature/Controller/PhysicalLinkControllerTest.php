
<?php

use App\Models\Peripheral;
use App\Models\PhysicalLink;
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
    test('can display physical links index page', function () {
        PhysicalLink::factory()->count(3)->create();

        $response = $this->get(route('admin.links.index'));

        $response->assertOk();
        $response->assertViewIs('admin.links.index');;
        $response->assertViewHas('physicalLinks');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.links.index'));

        $response->assertForbidden();
    });

});

describe('create', function () {
    test('can display create form', function () {
        $response = $this->get(route('admin.links.create'));

        $response->assertOk();
        $response->assertViewIs('admin.links.create');
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.links.create'));

        $response->assertForbidden();
    });

});

describe('show', function () {

    test('can display object', function () {
        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->get(route('admin.links.show', $physicalLink->id));

        $response->assertOk();
        $response->assertViewIs('admin.links.show');
        $response->assertViewHas(['link']);
        $response->assertSee($physicalLink->id);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->get(route('admin.links.show', $physicalLink->id));

        $response->assertForbidden();
    });

});

describe('edit', function () {
    test('can display edit form', function () {
        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->get(route('admin.links.edit', $physicalLink));

        $response->assertOk();
        $response->assertViewIs('admin.links.edit');
        $response->assertViewHas(['devices', 'link']);
        $response->assertSee($physicalLink->id);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->get(route('admin.links.edit', $physicalLink));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update PhysicalLink', function () {

        $peripheralSrc = Peripheral::factory()->create();
        $peripheralDest = Peripheral::factory()->create();
        $srcPort = fake()->word();
        $destPort = fake()->word();

        $physicalLink = PhysicalLink::factory()->create();

        $data = [
            'src_id' => 'PER_' . $peripheralSrc->id,
            'src_port' => $srcPort,
            'dest_id' => 'PER_' . $peripheralDest->id,
            'dest_port' => $destPort,
        ];

        $response = $this->put(
            route('admin.links.update', $physicalLink),
            $data);
        $response->assertRedirect(route('admin.links.index'));

        $this->assertDatabaseHas('physical_links',
            [
                'src_port' => $srcPort,
                'dest_port' => $destPort,
                'peripheral_src_id' => $peripheralSrc->id,
                'peripheral_dest_id' => $peripheralDest->id
            ]);;
    });
});

describe('destroy', function () {
    test('can delete PhysicalLink', function () {
        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->delete(route('admin.links.destroy', $physicalLink->id));
        $response->assertRedirect(route('admin.links.index'));

        $this->assertSoftDeleted('physical_links', ['id' => $physicalLink->id]);

        $physicalLink->refresh();
        expect($physicalLink->deleted_at)->not->toBeNull()
            ->and($physicalLink->trashed())->toBeTrue();

    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->delete(route('admin.links.destroy', $physicalLink));

        $response->assertForbidden();
    });
});

describe('massDestroy', function () {
    test('can delete multiple phones', function () {
        $phones = PhysicalLink::factory()->count(3)->create();
        $ids = $phones->pluck('id')->toArray();

        $response = $this->delete(route('admin.links.massDestroy'), ['ids' => $ids]);
        $response->assertNoContent();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('physical_links', ['id' => $id]);
        }
    });

    test('returns no content status', function () {
        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->delete(route('admin.links.massDestroy'), [
            'ids' => [$physicalLink->id],
        ]);

        $response->assertStatus(204);
    });

    test('denies access without permission', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $physicalLink = PhysicalLink::factory()->create();

        $response = $this->delete(route('admin.links.massDestroy'), [
            'ids' => [$physicalLink->id],
        ]);

        $response->assertForbidden();
    });


});

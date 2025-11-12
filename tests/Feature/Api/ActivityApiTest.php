<?php

use App\Models\Activity;
use App\Models\User;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);
});

// Helper pour créer un payload valide
function validActivityPayload(): array
{
    return [
        'name' => 'Test Activity',
        'description' => 'Test Description',
    ];
}

// ============================================================
// Tests pour l'API Activities
// ============================================================

it('forbids listing activities without permission', function () {
    // Créer un utilisateur sans permissions et l'authentifier avec Passport
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->getJson('/api/activities')
        ->assertForbidden();
});

it('lists activities when permitted', function () {
    // Utiliser l'utilisateur admin (id=1) qui a toutes les permissions
    $user = User::find(1);
    Passport::actingAs($user);

    Activity::factory()->count(3)->create();

    $this->getJson('/api/activities')
        ->assertOk()
        ->assertJsonCount(3);
});

it('forbids creating an activity without permission', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $this->postJson('/api/activities', validActivityPayload())
        ->assertForbidden();
});

it('creates an activity when permitted', function () {
    $user = User::find(1);
    Passport::actingAs($user);

    $this->postJson('/api/activities', validActivityPayload())
        ->assertCreated()
        ->assertJson(['name' => 'Test Activity']);

    $this->assertDatabaseHas('activities', ['name' => 'Test Activity']);
});

it('shows a single activity when permitted', function () {
    $user = User::find(1);
    Passport::actingAs($user);

    $activity = Activity::factory()->create(['name' => 'My Activity']);

    $this->getJson("/api/activities/{$activity->id}")
        ->assertOk()
        ->assertJson(['data' => ['name' => 'My Activity']]);
});

it('forbids showing an activity without permission', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $activity = Activity::factory()->create();

    $this->getJson("/api/activities/{$activity->id}")
        ->assertForbidden();
});

it('updates an activity when permitted', function () {
    $user = User::find(1);
    Passport::actingAs($user);

    $activity = Activity::factory()->create(['name' => 'Old Name']);

    $this->putJson("/api/activities/{$activity->id}", ['name' => 'Updated Name'])
        ->assertOk();

    $this->assertDatabaseHas('activities', ['name' => 'Updated Name']);
});

it('forbids updating an activity without permission', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $activity = Activity::factory()->create(['name' => 'Old Name']);

    $this->putJson("/api/activities/{$activity->id}", ['name' => 'Updated Name'])
        ->assertForbidden();
});

it('deletes an activity when permitted', function () {
    $user = User::find(1);
    Passport::actingAs($user);

    $activity = Activity::factory()->create();

    $this->deleteJson("/api/activities/{$activity->id}")
        ->assertOk();

    $this->assertSoftDeleted('activities', ['id' => $activity->id]);
});

it('forbids deleting an activity without permission', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $activity = Activity::factory()->create();

    $this->deleteJson("/api/activities/{$activity->id}")
        ->assertForbidden();
});

it('mass destroys activities when permitted', function () {
    $user = User::find(1);
    Passport::actingAs($user);

    $activities = Activity::factory()->count(3)->create();
    $ids = $activities->pluck('id')->toArray();

    // ✅ Utiliser deleteJson au lieu de postJson
    $this->deleteJson('/api/activities/mass-destroy', ['ids' => $ids])
        ->assertNoContent();

    foreach ($ids as $id) {
        $this->assertSoftDeleted('activities', ['id' => $id]);
    }
});

it('forbids mass destroy without permission', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $activities = Activity::factory()->count(3)->create();
    $ids = $activities->pluck('id')->toArray();

    // ✅ Utiliser deleteJson au lieu de postJson
    $this->deleteJson('/api/activities/mass-destroy', ['ids' => $ids])
        ->assertForbidden();
});

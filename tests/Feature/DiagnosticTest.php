<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mercator\Core\Models\Role;
use Mercator\Core\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('diagnostic: database connection is configured', function () {
    expect(DB::connection())->not->toBeNull();
    dump('✓ Database connection OK');
});

test('diagnostic: migrations are running', function () {
    $this->artisan('migrate:fresh');
    
    expect(Schema::hasTable('users'))->toBeTrue();
    expect(Schema::hasTable('roles'))->toBeTrue();
    dump('✓ Migrations tables exist');
});

test('diagnostic: can create role', function () {
    $role = Role::create(['title' => 'Test Role']);
    
    expect($role->exists)->toBeTrue();
    expect($role->title)->toBe('Test Role');
    dump('✓ Can create Role model');
});

test('diagnostic: can create user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'login' => 'test.user',
        'password' => bcrypt('password'),
    ]);
    
    expect($user->exists)->toBeTrue();
    expect($user->login)->toBe('test.user');
    dump('✓ Can create User model');
});

test('diagnostic: check TestCase setup', function () {
    dump('TestCase class: ' . get_class($this));
    dump('Traits: ' . implode(', ', class_uses_recursive($this)));
});

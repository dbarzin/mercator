
<?php

use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mercator\Core\Models\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

describe('import', function () {

    test('can display import page', function () {

        $response = $this->get(route('admin.config.import'));

        $response->assertOk();
        $response->assertViewIs('admin.import');
    });

    test('can export entities', function () {

        $response = $this->post(
            route('admin.config.export'),
            ['object'=>'Entity']);

        $response->assertOk();
        $response->assertHeader('Content-Disposition');

        $disposition = $response->headers->get('Content-Disposition');
        expect($disposition)->toBeString()
            ->and(strtolower($disposition))->toContain('attachment;')
            ->and(strtolower($disposition))->toContain('filename=')
            ->and(strtolower($disposition))->toContain('.xlsx');

        $base = $response->baseResponse;
        expect($base)->toBeInstanceOf(BinaryFileResponse::class);

        $path = $base->getFile()->getPathname();
        expect(is_file($path))->toBeTrue();

        $content = file_get_contents($path);
        expect(strlen($content))->toBeGreaterThan(100);

        // DOCX/XLSX sont des ZIP → début "PK"
        expect(substr($content, 0, 2))->toBe('PK');
    });

});

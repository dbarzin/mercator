<?php
// tests/Feature/Console/CPESyncCommandTest.php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('test mercator:cpe-sync --now --max=10', function () {
    // Force un last_run très ancien pour garantir la présence des filtres

    $exit = $this->artisan('mercator:cpe-sync', [
        '--now' => true,
        '--max' => 10,
    ])->run();

    expect($exit)->toBe(0);

    expect(DB::table('cpe_vendors')->count())->toBeGreaterThan(0);
    expect(DB::table('cpe_products')->count())->toBeGreaterThan(0);
    expect(DB::table('cpe_versions')->count())->toBeGreaterThan(0);
});

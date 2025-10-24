<?php
// tests/Feature/Console/CPESyncCommandTest.php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('test mercator:certificate-expiration', function () {
    // Force un last_run très ancien pour garantir la présence des filtres

    $exit = $this->artisan('mercator:certificate-expiration', [
    ])->run();

    expect($exit)->toBe(0);

});

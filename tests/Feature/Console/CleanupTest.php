<?php
// tests/Feature/Console/CPESyncCommandTest.php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('test mercator:cleanup', function () {
    // Force un last_run très ancien pour garantir la présence des filtres

    $exit = $this->artisan('mercator:cleanup')->run();

    expect($exit)->toBe(0);

});

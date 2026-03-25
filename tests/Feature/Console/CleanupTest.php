<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('test mercator:certificate-expiration', function () {
    $exit = $this->artisan('mercator:cleanup')->run();

    expect($exit)->toBe(0);

    // Asserts éventuels...
});

<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('test mercator:certificate-expiration', function () {
    $exit = Artisan::call('mercator:certificate-expiration');

    expect($exit)->toBe(0);
});

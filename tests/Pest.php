<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Here we bind Pest to Laravel's base TestCase class. This enables access
| to Laravel-specific helpers like actingAs, get, postJson, etc.
|
*/

uses(TestCase::class)->in('Feature', 'Unit'); // ✅ This makes all your Feature & Unit tests use Laravel's TestCase
uses(RefreshDatabase::class)->in('Feature');  // ✅ Optional: resets DB after each test (if using DB)

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| You can define custom expectations here using Pest's fluent API.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Define any global helper functions you'd like to use across your tests.
|
*/

function something()
{
    // ..
}

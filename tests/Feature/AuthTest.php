<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->loginUrl = '/api/login';
    $this->registerUrl = '/api/register';
    $this->logoutUrl = '/api/logout';
});

it('allows user to register successfully', function () {
    $userData = [
        'name'                  => 'Zeyad',
        'email'                 => 'zeyad@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson($this->registerUrl, $userData);

    $response->assertCreated();
    $response->assertJsonStructure(['data', 'token']);
    $this->assertDatabaseHas('users', [
        'email' => 'zeyad@example.com',
    ]);
});

it('allows user to log in with correct credentials', function () {
    $user = User::factory()->create([
        'email'    => 'zeyad@example.com',
        'password' => bcrypt('12345'),
    ]);

    $response = $this->postJson($this->loginUrl, [
        'email'    => 'zeyad@example.com',
        'password' => '12345',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['data', 'token']);
});

it('rejects login with wrong password', function () {
    User::factory()->create([
        'email'    => 'test@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson($this->loginUrl, [
        'email'    => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Email Or Password Is Wrong',
    ]);
});

it('allows user to logout successfully', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = actingAs($user)
        ->postJson($this->logoutUrl);

    $response->assertOk();
    $response->assertJson([
        'message' => 'Logged out',
    ]);
});

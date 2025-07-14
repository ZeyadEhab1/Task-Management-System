<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('allows user to log in with correct credentials', function () {
    $user = User::factory()->create([
        'name' => "zeyad",
        'email'    => 'zeyad@example.com',
        'password' => bcrypt('12345'),
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'zeyad@example.com',
        'password' => '12345',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'token']);
});

it('rejects login with wrong password', function () {
    $user = User::factory()->create([
        'email'    => 'test@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Email Or Password Is Wrong',
    ]);
});

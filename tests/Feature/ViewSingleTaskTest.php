<?php

use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'user']);

    $this->manager = User::factory()->create();
    $this->manager->assignRole('manager');

    $this->user = User::factory()->create();
    $this->user->assignRole('user');

    $this->otherUser = User::factory()->create();
    $this->otherUser->assignRole('user');

    $this->userTask = Task::factory()->create([
        'user_id' => $this->user->id,
        'status'  => TaskStatusEnum::Pending->value,
    ]);

    $this->otherUserTask = Task::factory()->create([
        'user_id' => $this->otherUser->id,
        'status'  => TaskStatusEnum::Pending->value,
    ]);

    $this->baseUrl = '/api/tasks';
});

it('allows manager to view any task', function () {
    $response = actingAs($this->manager)->getJson("{$this->baseUrl}/{$this->userTask->id}");

    $response->assertOk();
    $response->assertJsonFragment([
        'id'    => $this->userTask->id,
        'title' => $this->userTask->title,
    ]);
});

it('allows assigned user to view their task', function () {
    $response = actingAs($this->user)->getJson("{$this->baseUrl}/{$this->userTask->id}");

    $response->assertOk();
    $response->assertJsonFragment([
        'id'    => $this->userTask->id,
        'title' => $this->userTask->title,
    ]);
});

it('prevents users from viewing tasks not assigned to them', function () {
    $response = actingAs($this->user)->getJson("{$this->baseUrl}/{$this->otherUserTask->id}");

    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'Unauthorized',
    ]);
});

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

    $this->baseUrl = '/api/create-task';

    $this->manager = User::factory()->create();
    $this->manager->assignRole('manager');

    $this->user = User::factory()->create();
    $this->user->assignRole('user');

    $this->assignee = User::factory()->create();

    // Example task data template for reuse
    $this->taskData = [
        'title'       => 'Test Task',
        'description' => 'Some description',
        'due_date'    => now()->addWeek()->format('Y-m-d'),
        'user_id'     => $this->assignee->id,
    ];
});

it('allows manager to create a task', function () {
    $response = actingAs($this->manager)->postJson($this->baseUrl, $this->taskData);

    $response->assertCreated()
        ->assertJsonFragment([
            'title'       => $this->taskData['title'],
            'description' => $this->taskData['description'],
            'status'      => TaskStatusEnum::Pending->value,
        ]);

    $this->assertDatabaseHas('tasks', [
        'title'   => $this->taskData['title'],
        'user_id' => $this->taskData['user_id'],
    ]);
});

it('prevents non-manager user from creating a task', function () {
    $response = actingAs($this->user)->postJson($this->baseUrl, $this->taskData);

    $response->assertForbidden()
        ->assertJson(['message' => 'This action is unauthorized.']);

    $this->assertDatabaseMissing('tasks', [
        'title' => 'Test Task',
    ]);
});


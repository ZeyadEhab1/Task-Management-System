<?php

use App\Models\User;
use App\Models\Task;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'manager']);
    $this->baseUrl = '/api/tasks/';
});

it('allows manager to update full task details', function () {
    $manager = User::factory()->create();
    $manager->assignRole('manager');

    $assignee = User::factory()->create();

    $task = Task::factory()->create([
        'user_id'     => $assignee->id,
        'title'       => 'Old Title',
        'description' => 'Old Description',
        'status'      => TaskStatusEnum::Pending->value,
    ]);

    $updateData = [
        'title'       => 'Updated Title',
        'description' => 'Updated Description',
        'due_date'    => now()->addWeek()->format('Y-m-d'),
        'user_id'     => $assignee->id,
        'status'      => TaskStatusEnum::Canceled->value,
    ];

    $response = actingAs($manager)
        ->putJson($this->baseUrl . $task->id, $updateData);

    $response->assertOk()
        ->assertJsonFragment(['title' => 'Updated Title']);

    expect($task->fresh()->title)->toBe('Updated Title');
});

it('allows assigned user to update only status', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status'  => TaskStatusEnum::Pending->value,
    ]);

    $response = actingAs($user)
        ->putJson($this->baseUrl . $task->id, [
            'status' => TaskStatusEnum::Completed->value,
        ]);

    $response->assertOk()
        ->assertJsonFragment(['status' => TaskStatusEnum::Completed->value]);

    expect($task->fresh()->status->value)->toBe(TaskStatusEnum::Completed->value);
});

it('prevents non-manager and unassigned user from updating task', function () {
    $otherUser = User::factory()->create();

    $task = Task::factory()->create(); // assigned to someone else

    $response = actingAs($otherUser)
        ->putJson($this->baseUrl . $task->id, [
            'status' => TaskStatusEnum::Canceled->value,
        ]);

    $response->assertForbidden()
        ->assertJson(['message' => 'This action is unauthorized.']);
});

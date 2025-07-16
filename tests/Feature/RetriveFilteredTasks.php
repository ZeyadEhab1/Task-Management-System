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

    $this->user1 = User::factory()->create();
    $this->user1->assignRole('user');

    $this->user2 = User::factory()->create();
    $this->user2->assignRole('user');

    $this->task1 = Task::factory()->create([
        'title'    => 'User1 Task',
        'user_id'  => $this->user1->id,
        'status'   => TaskStatusEnum::Pending->value,
        'due_date' => now()->addDays(5),
    ]);

    $this->task2 = Task::factory()->create([
        'title'    => 'User2 Task',
        'user_id'  => $this->user2->id,
        'status'   => TaskStatusEnum::Completed->value,
        'due_date' => now()->addDays(10),
    ]);

    $this->subtask = Task::factory()->create([
        'title'     => 'Subtask',
        'user_id'   => $this->user1->id,
        'parent_id' => $this->task1->id,
        'status'    => TaskStatusEnum::Pending->value,
        'due_date'  => now()->addDays(4),
    ]);
});


it('allows manager to retrieve all parent tasks', function () {
    $response = actingAs($this->manager)->getJson('/api/tasks');
    $response->assertOk();
    $response->assertJsonCount(2, 'data');
});

it('allows user to retrieve only their own parent tasks', function () {
    $response = actingAs($this->user1)->getJson('/api/tasks');
    $response->assertOk();
    $response->assertJsonFragment(['title' => 'User1 Task']);
    $response->assertJsonMissing(['title' => 'User2 Task']);
});

it('filters tasks by status and date range', function () {
    $start = now()->format('Y-m-d');
    $end = now()->addDays(6)->format('Y-m-d');

    $response = actingAs($this->manager)->getJson("/api/tasks?status=pending&start_date={$start}&end_date={$end}");
    $response->assertOk();
    $response->assertJsonFragment(['title' => 'User1 Task']);
    $response->assertJsonMissing(['title' => 'User2 Task']);
});

<?php

use App\Models\Task;
use App\Models\User;
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

    $this->task = Task::factory()->create([
        'title'    => 'Deletable Task',
        'user_id'  => $this->user->id,
        'due_date' => now()->addDays(7),
    ]);
});

it('allows manager to delete a task', function () {
    $response = actingAs($this->manager)->deleteJson("/api/tasks/{$this->task->id}");

    $response->assertOk();
    $response->assertJson([
        'message' => 'Task deleted successfully.',
    ]);

    expect(Task::find($this->task->id))->toBeNull();
});

it('prevents non-manager from deleting a task', function () {
    $response = actingAs($this->user)->deleteJson("/api/tasks/{$this->task->id}");

    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'Unauthorized',
    ]);

    expect(Task::find($this->task->id))->not->toBeNull();
});

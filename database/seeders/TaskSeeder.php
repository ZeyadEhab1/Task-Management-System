<?php

// database/seeders/TaskSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatus;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $task1 = Task::create([
            'title'       => 'Parent Task 1',
            'description' => 'This is the root task for others.',
            'status'      => TaskStatus::Pending,
            'user_id'     => $user?->id,
            'due_date'    => now()->addDays(7),
        ]);

        $task2 = Task::create([
            'title'       => 'Dependent Task 2',
            'description' => 'Depends on Task 1',
            'status'      => TaskStatus::Pending,
            'parent_id'   => $task1->id,
            'user_id'     => $user?->id,
            'due_date'    => now()->addDays(3),
        ]);

        $task3 = Task::create([
            'title'       => 'Dependent Task 3',
            'description' => 'Depends on Task 1',
            'status'      => TaskStatus::Pending,
            'parent_id'   => $task1->id,
            'user_id'     => $user?->id,
            'due_date'    => now()->addDays(4),
        ]);

        $task4 = Task::create([
            'title'       => 'Dependent Task 4',
            'description' => 'Depends on Task 1',
            'status'      => TaskStatus::Pending,
            'parent_id'   => $task1->id,
            'user_id'     => $user?->id,
            'due_date'    => now()->addDays(5),
        ]);

        $task5 = Task::create([
            'title'       => 'Independent Task 5',
            'description' => 'Stands alone.',
            'status'      => TaskStatus::Pending,
            'user_id'     => $user?->id,
            'due_date'    => now()->addDays(6),
        ]);
    }
}

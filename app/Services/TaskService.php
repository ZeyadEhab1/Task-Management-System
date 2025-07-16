<?php

namespace App\Services;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function getFilteredTasks(Request $request)
    {
        $query = Task::query()
            ->onlyParents()
            ->with(['user', 'children']);

        if (!Auth::user()->hasRole('manager')) {
            $query->forUser(Auth::id());
        } else {
            $query->filterByUser($request->user_id);
        }

        return $query
            ->filterByStatus($request->status)
            ->filterByDateRange($request->start_date, $request->end_date)
            ->get();
    }

    public function createTask(array $data): Task
    {
        $task = Task::create([
            ...$data,
            'status' => TaskStatusEnum::Pending->value,
        ]);

        $task->load(['user', 'children']);
        return $task;
    }

    public function updateTask($request, Task $task): Task
    {
        $user = Auth::user();

        $data = $user->hasRole('manager')
            ? $request->validated()
            : ['status' => $request->status];

        $task->update($data);
        $task->load(['user', 'children']);

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}

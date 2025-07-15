<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query()
            ->onlyParents()
            ->with(['user', 'children']);

        if (!auth()->user()->hasRole('manager')) {
            $query->forUser(auth()->id());
        } else {
            $query->filterByUser($request->user_id);
        }

        $tasks = $query
            ->filterByStatus($request->status)
            ->filterByDateRange($request->start_date, $request->end_date)
            ->get();

        return TaskResource::collection($tasks);
    }


    public function store(StoreTaskRequest $request): TaskResource
    {
        if (!Auth::user()->hasRole('manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'user_id'     => $request->user_id,
            'parent_id'   => $request->parent_id,
            'status'      => TaskStatusEnum::Pending->value,
        ]);
        $task->load(['user', 'children']);
        return new TaskResource($task);
    }


    public function show(Task $task): TaskResource|JsonResponse
    {
        $user = Auth::user();

        if (
            $user->hasRole('manager') ||
            $task->user_id === $user->id
        ) {
            $task->load(['user', 'children']);
            return new TaskResource($task);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }


    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        $user = Auth::user();

        if ($user->hasRole('manager')) {
            $task->update($request->validated());
        } elseif ($user->id === $task->user_id) {
            $task->update([
                'status' => $request->status,
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $task->load(['user', 'children']);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}

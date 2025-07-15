<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }


    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        $user = Auth::user();

        if ($user->hasRole('manager')) {
            $task->update($request->validated());
        }
        elseif ($user->id === $task->user_id) {
            $task->update([
                'status' => $request->status,
            ]);
        }
        else {
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

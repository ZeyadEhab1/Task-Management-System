<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->getFilteredTasks($request);

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): TaskResource|JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->taskService->createTask($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task): TaskResource|JsonResponse
    {
        $this->authorize('view', $task);

        $task->load(['user', 'children']);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        $this->authorize('update', $task);

        $task = $this->taskService->updateTask($request, $task);

        return new TaskResource($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return response()->json(['message' => 'Task deleted successfully.']);
    }
}

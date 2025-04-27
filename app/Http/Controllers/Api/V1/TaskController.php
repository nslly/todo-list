<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     * 
     * @return JsonResponse
     * 
     */
    public function index(): JsonResponse
    {
        try {

            $tasks = Task::where('user_id', auth()->id())->latest()->get();

            return response()->json([
                'message' => 'Tasks retrieved successfully.',
                'tasks' => TaskResource::collection($tasks),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tasks.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     * 
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();

            $validated['user_id'] = auth()->id();

            $task = Task::create($validated);
            return response()->json([
                'message' => 'Task created successfully.',
                'task' => new TaskResource($task),
            ], 201);
    
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        Gate::authorize('view', $task);

        return response()->json([
            'message' => 'Task retrieved successfully.',
            'task' => new TaskResource($task),
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     * @param TaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);


        try {
            $validated = $request->validated();

            $task->update($validated);

            return response()->json([
                'message' => 'Task updated successfully.',
                'task' => new TaskResource($task),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     * 
     * @param Task $task
     * @return JsonResponse
     * 
     */
    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);

        try {
            $task->delete();

            return response()->json([
                'message' => 'Task deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

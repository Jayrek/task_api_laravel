<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
       $task = Task::create($request->validated());
       
       return TaskResource::make($task);

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
      return response()->json([
         'status' => true,
         'message' => 'success',
         'data' => TaskResource::make($task)
      ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
       $task->update($request->validated());
       return response()->json([
         'status' => true,
         'message' => 'success',
         'data' => TaskResource::make($task)
       ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
       $task->delete();
       return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Filters\TaskFilter;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::query();
        TaskFilter::handle($tasks);
        $tasks = $tasks->paginate($request->get('limit', config('users.default_pagination', 15)));

        return response()->json([
            'message' => 'Tasks',
            'data' => TaskResource::collection($tasks),
            'paginationData' => [
                'total' => $tasks->total(),
                'totalInPage' => $tasks->perPage(),
                'totalPages' => $tasks->lastPage(),
                'currentPage' => $tasks->currentPage(),
                'limit' => $tasks->perPage(),
                'next' => $tasks->hasMorePages() ? $tasks->nextPageUrl() : null,
                'prev' => $tasks->currentPage() > 1 ? $tasks->previousPageUrl() : null,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->get('title'),
            'description' => $request->get('description')
        ]);

        $task->users()->attach(Auth::user());

        return response()->json([
            'message' => 'Stored successfully',
            'data' => TaskResource::make($task)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TaskRequest  $request
     * @param  Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update([
            'title' => $request->get('title'),
            'description' => $request->get('description')
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => TaskResource::make($task)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if ($task->hasMultipleUsers() || !Auth::user()->isAdministrator()) {
            $task->users()->detach(Auth::user());
        } else {
            $task->delete();
        }

        return response()->json([
            'message' => 'Task deleted.',
            'data' => null
        ]);
    }

    /**
     * Assign this task to authenticated admin
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function assignToMe(Task $task)
    {
        $this->authorize('assign-task');

        $task->users()->attach(Auth::user());

        return response()->json([
            'message' => 'Task assigned.',
            'data' => null
        ]);
    }
}

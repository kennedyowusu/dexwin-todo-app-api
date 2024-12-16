<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TodoRequest;
use App\Http\Resources\v1\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @group Todos
     * @queryParam status string Filter todos by status. Example: completed
     * @queryParam search string Search todos by title or details. Example: project
     * @queryParam sortBy string Sort todos by a field. Example: created_at
     * @queryParam order string Sort order. Must be one of asc or desc. Example: desc
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "New Todo",
     *       "details": "Details about the todo",
     *       "status": "completed",
     *       "user": {
     *         "id": 1,
     *         "name": "Kennedy Owusu",
     *         "email": "kennedy@example.com"
     *       },
     *       "created_at": "2024-12-15T12:00:00Z",
     *       "updated_at": "2024-12-15T12:00:00Z",
     *       "deleted_at": null
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        // Apply filters
        if ($request->has('status')) {
            $query->filterByStatus($request->status);
        }

        // Apply search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Sorting
        if ($request->has('sortBy') && $request->has('order')) {
            $query->orderBy($request->sortBy, $request->order);
        }

        // Paginate results
        $todos = $query->paginate(10);

        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @group Todos
     * @bodyParam title string required The title of the todo. Example: Finish project
     * @bodyParam details string The details of the todo. Example: Complete API implementation
     * @bodyParam status string required The status of the todo. Must be one of completed, in progress, or not started. Example: in progress
     * @bodyParam user_id integer required The ID of the user. Example: 1
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "title": "Finish project",
     *     "details": "Complete API implementation",
     *     "status": "in progress",
     *     "user": {
     *       "id": 1,
     *       "name": "Kennedy Owusu",
     *       "email": "kennedy@example.com"
     *     },
     *     "created_at": "2024-12-15T12:00:00Z",
     *     "updated_at": "2024-12-15T12:00:00Z",
     *     "deleted_at": null
     *   }
     * }
     */
    public function store(TodoRequest $request)
    {
        $todo = Todo::create($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     *
     * @group Todos
     * @urlParam todo integer required The ID of the todo. Example: 1
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Finish project",
     *     "details": "Complete API implementation",
     *     "status": "in progress",
     *     "user": {
     *       "id": 1,
     *       "name": "Kennedy Owusu",
     *       "email": "kennedy@example.com"
     *     },
     *     "created_at": "2024-12-15T12:00:00Z",
     *     "updated_at": "2024-12-15T12:00:00Z",
     *     "deleted_at": null
     *   }
     * }
     * @response 404 {
     *   "message": "Todo not found."
     * }
     */
    public function show(Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @group Todos
     * @urlParam todo integer required The ID of the todo. Example: 1
     * @bodyParam title string The updated title of the todo. Example: Update title
     * @bodyParam status string The updated status of the todo. Example: completed
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Update title",
     *     "details": "Complete API implementation",
     *     "status": "completed",
     *     "user": {
     *       "id": 1,
     *       "name": "Kennedy Owusu",
     *       "email": "kennedy@example.com"
     *     },
     *     "created_at": "2024-12-15T12:00:00Z",
     *     "updated_at": "2024-12-15T12:00:00Z",
     *     "deleted_at": null
     *   }
     * }
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        $todo->update($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @group Todos
     * @urlParam todo integer required The ID of the todo. Example: 1
     * @response 200 {
     *   "message": "Todo deleted successfully."
     * }
     * @response 404 {
     *   "message": "Todo not found."
     * }
     */
    public function destroy(Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully.'], 200);
    }
}

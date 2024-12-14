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
     */
    public function store(TodoRequest $request)
    {
        $todo = Todo::create($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        $todo->update($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo = Todo::findOrFail($todo->id);

        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully.'], 200);
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\TodoController;

Route::prefix('v1')->group(function () {
    Route::get('todos', [TodoController::class, 'index']);
    Route::post('todos', [TodoController::class, 'store']);
    Route::get('todos/{todo}', [TodoController::class, 'show']);
    Route::put('todos/{todo}', [TodoController::class, 'update']);
    Route::delete('todos/{todo}', [TodoController::class, 'destroy']);
});

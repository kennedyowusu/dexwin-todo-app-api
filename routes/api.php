<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\TodoController;

Route::prefix('v1')->group(function () {
    Route::apiResource('todos', TodoController::class);
});

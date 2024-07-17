<?php

use App\Http\Controllers\MotoController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\TasksController;
use App\Models\Subtarefas;
use App\Models\Subtasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('tasks')->group(function () {
    Route::controller(TasksController::class)->group(function () {
        Route::post('/', 'create');
        Route::get('/', 'index');
        Route::get('/{tasks}', 'show');
        Route::put('/{tasks}', 'update');
        Route::patch('/{tasks}/due_date', 'patch');
        Route::patch('/{tasks}/status', 'updateStatus');
        Route::delete('/{tasks}', 'destroy');
        Route::get('/filter/today', 'filterToday'); 
        Route::get('/filter/overdue', 'filterOverdue');

    
        Route::prefix('subtasks')->group(function () {
            Route::post('/', [SubtasksController::class, 'create']);
            Route::get('/', [SubtasksController::class, 'index']);
            Route::get('/{subtasks}', [SubtasksController::class, 'show']);
            Route::put('/{id}', [SubtasksController::class, 'update']);
            Route::patch('/{id}', [SubtasksController::class, 'updateStatus']);
            Route::delete('/{subtasks}', [SubtasksController::class, 'destroy']);
        });
    });
});















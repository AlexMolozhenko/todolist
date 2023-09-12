<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'getUser']);

    Route::prefix('task')->group(function () {

        Route::post('list',[TaskController::class,'getTasks']);

        Route::post('user_task',[TaskController::class,'getUserTasks']);

        Route::post('subtask',[TaskController::class,'getSubtasks']);

        Route::get('task_id/{task_id}',[TaskController::class,'getTaskById']);

        Route::post('create_task',[TaskController::class,'createTask']);

        Route::post('create_subtask',[TaskController::class,'createSubtask']);

        Route::post('update',[TaskController::class,'updateTask']);

        Route::post('completed',[TaskController::class,'completedTask']);

        Route::post('delete',[TaskController::class,'deleteTask']);

    });
});

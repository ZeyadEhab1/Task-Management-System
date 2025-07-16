<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::controller(TaskController::class)->group(function () {
        Route::post('/create-task', 'store');
        Route::put('/tasks/{task}', 'update');
        Route::get('/tasks/{task}', 'show');
        Route::get('/tasks', 'index');
        Route::delete('/tasks/{task}', 'destroy');


    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

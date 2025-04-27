<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\Auth\LoginController;



Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'store']);
});


Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'auth:api']], function () {
    Route::post('/logout', [LoginController::class, 'destroy']);

    Route::apiResource('tasks', TaskController::class)->except(['create', 'edit']);
});

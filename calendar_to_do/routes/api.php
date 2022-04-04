<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Route::resource('products', ProductController::class);
    Route::resource('todo', ToDoController::class)->except('create','edit');
    Route::resource('generalSetting', GeneralSettingController::class)->only('show','update');
    Route::resource('calendar', CalendarController::class)->except('create','edit');
    Route::resource('user', UserController::class);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
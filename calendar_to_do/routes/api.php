<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('todo', ToDoController::class)->except('create', 'edit');
    Route::resource('generalSetting', GeneralSettingController::class)->only('show', 'update');
    Route::resource('calendar', CalendarController::class)->except('create', 'edit');
    Route::get('calendars/search', [CalendarController::class, "search"]);
    Route::apiResource('users', UserController::class)->only('update');
});
// パスワードリセット関連
Route::prefix('passwordReset')->name('password_reset.')->group(function () {
    Route::prefix('email')->name('email.')->group(function () {
        Route::post('/', [PasswordController::class, 'sendEmailResetPassword'])->name('send');
        Route::get('/send_complete', [PasswordController::class, 'sendComplete'])->name('send_complete');
    });
    Route::get('/edit', [PasswordController::class, 'edit'])->name('edit');
    Route::post('/update', [PasswordController::class, 'update'])->name('update');
    Route::get('/edited', [PasswordController::class, 'edited'])->name('edited');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

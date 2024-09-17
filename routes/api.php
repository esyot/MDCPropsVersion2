<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| that contains the "api" middleware group. Enjoy building your API!
|
*/

// Public API Routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Authenticated API Routes
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']); // Example route for fetching the authenticated user

    Route::get('/transactions', [TransactionController::class, 'index'])->name('api.transactions');
    Route::get('/date-view/{date}', [DashboardController::class, 'dateView'])->name('api.dateView');
    Route::post('/message-send', [MessageController::class, 'messageSend'])->name('api.messageSend');
    Route::post('/dark-mode', [SettingController::class, 'darkMode'])->name('api.darkMode');
    Route::post('/category-add', [CategoryController::class, 'create'])->name('api.categoryAdd');
    Route::post('/item-add', [ItemController::class, 'create'])->name('api.itemAdd');
    Route::put('/item-update/{id}', [ItemController::class, 'update'])->name('api.itemUpdate');
    Route::get('/pending-approve/{id}', [TransactionController::class, 'approve'])->name('api.transactionApprove');
});

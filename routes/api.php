<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
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

// // Public API Routes
// Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// // Authenticated API Routes
// Route::middleware('auth:api')->group(function () {
//     Route::get('/admin/user', [AuthController::class, 'user']); // Example route for fetching the authenticated user

//     Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('api.transactions');
//     Route::get('/admin/date-view/{date}', [DashboardController::class, 'dateView'])->name('api.dateView');
//     Route::post('/admin/message-send-new', [MessageController::class, 'messageSendNew'])->name('api.messageSendNew');
//     Route::post('/admin/message-send', [MessageController::class, 'messageSend'])->name('api.messageSend');
//     Route::post('/admin/dark-mode', [SettingController::class, 'darkMode'])->name('api.darkMode');
//     Route::post('/admin/category-add', [CategoryController::class, 'create'])->name('api.categoryAdd');
//     Route::post('/admin/user-add', [UserController::class, 'create'])->name('api.userAdd');
//     Route::post('/admin/item-add', [ItemController::class, 'create'])->name('api.itemAdd');
//     Route::put('/admin/item-update/{id}', [ItemController::class, 'update'])->name('api.itemUpdate');
//     Route::get('/admin/pending-approve/{id}', [TransactionController::class, 'approve'])->name('api.transactionApprove');
//     Route::post('/admin/user-delete/{id}', [UserController::class, 'delete'])->name('api.userDelete');

//     route::get('/admin/item-search/{day}', [ItemController::class, 'search'])->name('api.itemSearch');

// });

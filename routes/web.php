<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SampleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    return view('test');
});

Route::get('/', [DashboardController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/login', [LoginController::class, 'index']);

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');

Route::get('calendar-move/{action}/{category}/{year}/{month}', [DashboardController::class, 'calendarMove'])->name('calendarMove');

Route::get('notification-list/{filter}', [NotificationController::class, 'notificationList'])->name('notificationList');

Route::get('messages', [MessageController::class, 'index'])->name('messages');

Route::get('chat-selected/{contact}', [MessageController::class, 'chatSelected'])->name('chatSelected');

Route::get('categories', [CategoryController::class, 'index'])->name('categories');

Route::get('items', [ItemController::class, 'index'])->name('items');

Route::get('/items-filter', [ItemController::class, 'itemsFilter'])->name('itemsFilter');

Route::get('/transactions-filter', [TransactionController::class, 'index'])->name('transactionsFilter');
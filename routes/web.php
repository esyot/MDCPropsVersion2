<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| that contains the "web" middleware group. Now create something great!
|
*/

// Public Routes
Route::get('/login', [LoginController::class, 'index'])->name('loginPage');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/test', function () {
    return view('test');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']); // Home route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('calendar-move/{action}/{category}/{year}/{month}', [DashboardController::class, 'calendarMove'])->name('calendarMove');
    Route::get('notification-list/{filter}', [NotificationController::class, 'notificationList'])->name('notificationList');
    Route::get('messages', [MessageController::class, 'index'])->name('messages');
    Route::get('chat-selected/{contact}', [MessageController::class, 'chatSelected'])->name('chatSelected');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('items', [ItemController::class, 'index'])->name('items');
    Route::get('/items-filter', [ItemController::class, 'itemsFilter'])->name('itemsFilter');
    Route::get('/transactions-filter', [TransactionController::class, 'filter'])->name('transactionsFilter');
    Route::get('/date-view/{date}', [DashboardController::class, 'dateView'])->name('dateView');
    Route::get('/date-custom', [DashboardController::class, 'dateCustom'])->name('dateCustom');
    Route::get('/isRead/{id}/{redirect_link}', [NotificationController::class, 'isRead'])->name('isRead');
    Route::get('/transaction-decline/{id}', [TransactionController::class, 'decline'])->name('transactionDecline');
    Route::get('/notification/read/all', [NotificationController::class, 'readAll'])->name('readAll');
    Route::get('/notification/delete/all', [NotificationController::class, 'deleteAll'])->name('deleteAll');
    Route::get('/message-is-reacted/{id}', [MessageController::class, 'messageReacted'])->name('messageReacted');
    Route::post('/message-send', [MessageController::class, 'messageSend'])->name('messageSend');
    Route::get('/contacts', [MessageController::class, 'contacts'])->name('contacts');
    Route::post('/dark-mode', [SettingController::class, 'darkMode'])->name('darkMode');
    Route::post('/transitions', [SettingController::class, 'transitions'])->name('transitions');
    Route::post('/category-add', [CategoryController::class, 'create'])->name('category-add');
    Route::post('/item-add', [ItemController::class, 'create'])->name('itemAdd');
    Route::put('/item-update/{id}', [ItemController::class, 'update'])->name('itemUpdate');
    Route::get('pending-approve/{id}', [TransactionController::class, 'approve'])->name('transactionApprove');
    Route::post('/transaction-create', [DashboardController::class, 'transactionAdd'])->name('transaction-create');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::post('/profile/update/{id}', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('/password/update/{id}', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');


    Route::get('users-manage', [UserController::class, 'index'])->name('users');
    Route::post('users-role-update', [UserController::class, 'roleUpdate'])->name('roleUpdate');
    Route::post('/user-add', [UserController::class, 'create'])->name('userAdd');
    Route::post('/user-delete/{id}', [UserController::class, 'delete'])->name('userDelete');

    Route::post('/message-new-send', [MessageController::class, 'messageNewSend'])->name('messageNewSend');

    Route::get('/item-search/{day}', [ItemController::class, 'search'])->name('itemSearch');

});

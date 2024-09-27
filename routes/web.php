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
use App\Http\Controllers\RolePermissionController;
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
Route::get('/admin/login', [LoginController::class, 'index'])->name('loginPage');
Route::post('/admin.login', [AuthController::class, 'login'])->name('login');
Route::get('test', function () {
    return view('admin.test');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']); // Home route
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/admin/calendar-move/{action}/{category}/{year}/{month}', [DashboardController::class, 'calendarMove'])->name('calendarMove');
    Route::get('/admin/notification-list/{filter}', [NotificationController::class, 'notificationList'])->name('notificationList');
    Route::get('/admin/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/admin/chat-selected/{contact}', [MessageController::class, 'chatSelected'])->name('chatSelected');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/admin/items', [ItemController::class, 'index'])->name('items');
    Route::get('/admin/items-filter', [ItemController::class, 'itemsFilter'])->name('itemsFilter');
    Route::get('/admin/transactions-filter', [TransactionController::class, 'filter'])->name('transactionsFilter');
    Route::get('/admin/date-view/{date}', [DashboardController::class, 'dateView'])->name('dateView');
    Route::get('/admin/date-custom', [DashboardController::class, 'dateCustom'])->name('dateCustom');
    Route::get('/admin/isRead/{id}/{redirect_link}', [NotificationController::class, 'isRead'])->name('isRead');
    Route::get('/admin/transaction-decline/{id}', [TransactionController::class, 'decline'])->name('transactionDecline');
    Route::get('/admin/notification/read/all', [NotificationController::class, 'readAll'])->name('readAll');
    Route::get('/admin/notification/delete/all', [NotificationController::class, 'deleteAll'])->name('deleteAll');
    Route::get('/admin/message-is-reacted/{id}', [MessageController::class, 'messageReacted'])->name('messageReacted');
    Route::post('/admin/message-send', [MessageController::class, 'messageSend'])->name('messageSend');
    Route::get('/admin/contacts', [MessageController::class, 'contacts'])->name('contacts');
    Route::post('/admin/dark-mode', [SettingController::class, 'darkMode'])->name('darkMode');
    Route::post('/admin/transitions', [SettingController::class, 'transitions'])->name('transitions');
    Route::post('/admin/category-add', [CategoryController::class, 'create'])->name('category-add');
    Route::post('/admin/item-add', [ItemController::class, 'create'])->name('itemAdd');
    Route::put('/admin/item-update/{id}', [ItemController::class, 'update'])->name('itemUpdate');
    Route::get('admin/pending-approve/{id}', [TransactionController::class, 'approve'])->name('transactionApprove');
    Route::post('/admin/transaction-create', [DashboardController::class, 'transactionAdd'])->name('transaction-create');
    Route::get('/admin/profile', [ProfileController::class, 'index'])->name('profile');

    Route::post('/admin/profile/update/{id}', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('/admin/password/update/{id}', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');


    Route::get('/admin/users-manage', [UserController::class, 'index'])->name('users');
    Route::post('/admin/users-role-update', [UserController::class, 'roleUpdate'])->name('roleUpdate');
    Route::post('/admin/user-add', [UserController::class, 'create'])->name('userAdd');
    Route::post('/admin/user-delete/{id}', [UserController::class, 'delete'])->name('userDelete');

    Route::post('/admin/message-new-send', [MessageController::class, 'messageNewSend'])->name('messageNewSend');

    Route::get('/admin/item-search/{day}', [ItemController::class, 'search'])->name('itemSearch');

    Route::get('/admin/message-bubble/{receiver_name}', [MessageController::class, 'messageBubble'])->name('messageBubble');
    Route::post('/admin/manage-categories-user-update/{category_id}', [RolePermissionController::class, 'managedCategoriesUpdate'])->name('managedCategoriesUpdate');
});

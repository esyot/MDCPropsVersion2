<?php
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\RenteeTrackingController;
use App\Http\Controllers\RenteeTransactionController;
use App\Http\Controllers\ReturnItemController;
use Illuminate\Support\Facades\Route;

//admin
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
use App\Http\Controllers\RenteeItemsController;
use App\Http\Controllers\RenteeCustomerServiceController;
use App\Http\Controllers\RenteeCartController;
use App\Http\Controllers\RenteeHomeController;
use App\Http\Controllers\RenteeController;
use App\Http\Controllers\ContactController;

//User Controller

use App\Http\Controllers\UserHomeController;

//Rentee Routes
Route::get('/', function () {
    return view('rentee.pages.welcome');
});

Route::get('/welcome', [RenteeHomeController::class, 'welcome'])->name('welcome');

Route::get('/rentee/items/{category_id}/{rentee}', [RenteeItemsController::class, 'index'])->name('userItems');

Route::get('/rentee/item/{id}', [RenteeItemsController::class, 'itemUnAvailableDates']);


Route::get('/rentee/items-filter/{category_id}/{rentee}', [RenteeItemsController::class, 'renteeItemsFilter'])->name('renteeItemsFilter');

Route::get('rentee/tracking', [RenteeTrackingController::class, 'index'])->name('tracking');


Route::get('/rentee/cart/{rentee}', [RenteeCartController::class, 'index'])->name('cart');

Route::get('/get-started', [RenteeController::class, 'create'])->name('getStarted');

Route::get('/home/{rentee}', [RenteeHomeController::class, 'home'])->name('home');

Route::get('/rentee/cancel-order/{rentee}', [RenteeController::class, 'cancelOrder'])->name('cancelOrder');

Route::get('/rentee/add-to-cart/{rentee}/{item}', [RenteeCartController::class, 'addToCart'])->name('addToCart');

Route::get('/rentee/back-to-home/{rentee}', [RenteeHomeController::class, 'backToHome'])->name('backToHome');

Route::get('/rentee/checkout/{rentee}', [RenteeCartController::class, 'checkout'])->name('checkout');

Route::get('/rentee/remove-item-in-cart/{id}/{rentee}', [RenteeCartController::class, 'removeItemInCart'])->name('removeItemInCart');

Route::post('/rentee/create-transaction/{rentee}', [RenteeTransactionController::class, 'store'])->name('renteeCreateTransaction');

Route::get('/rentee/track-transaction', [RenteeTrackingController::class, 'track'])->name('transactionTrack');


Route::get('/rentee/tracking-page', [RenteeTrackingController::class, 'index'])->name('tracking');

Route::get('/rentee/tracking', [RenteeTrackingController::class, 'fetch']);

Route::get('/rentee/transaction-done/{transaction}', [RenteeTransactionController::class, 'transactionDone'])->name('transactionDone');


Route::get('/rentee/reservation-cancel/{tracking_code}', [RenteeTransactionController::class, 'reservationCancel'])->name('rentee.reservation-cancel');
//Cashier Routes

Route::get('/cashier/welcome', [CashierController::class, 'welcome'])->name('cashier.welcome');

Route::get('/cashier/home', [CashierController::class, 'home'])->name('cashier.home');

Route::get('/cashier/reservations', [CashierController::class, 'reservations'])->name('cashier.reservations');

Route::get('/cashier/reservations-details/{tracking_code}', [CashierController::class, 'reservationDetails'])->name('cashier.reservation-details');

Route::get('/cashier/start-session', [CashierController::class, 'sessionStart'])->name('cashier.session-start');

Route::get('/cashier/reservation-search', [CashierController::class, 'search'])->name('cashier.reservation-search');

Route::post('/cashier/reservation-payment', [CashierController::class, 'payment'])->name('cashier.reservation-payment');

Route::get('/cashier/transactions', [Cashiercontroller::class, 'transactions'])->name('cashier.transactions');

Route::get('/cashier/notifications-filter/{action}', [CashierController::class, 'notificationsFilter'])->name('cashier.notifications-filter');

Route::get('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
Route::get('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');


Route::get('/admin/search-items-for-return', [ReturnItemController::class, 'search'])->name('admin.search-items-for-return');

// Public Routes
Route::get('/admin/login', [LoginController::class, 'index'])->name('loginPage');
Route::post('/admin.login', [AuthController::class, 'login'])->name('login');
Route::get('test', function () {
    return view('test');
});

Route::get('/admin/contacts-search', [ContactController::class, 'search'])->name('searchContact');

Route::get('/admin/users-filter', [UserController::class, 'filter'])->name('usersFilter');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics-index');

    Route::get('/admin/return-item', [ReturnItemController::class, 'index'])->name('admin.return-item');

    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management
    Route::get('/admin/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/admin/profile/update/{id}', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('/admin/password/update/{id}', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');

    // Notifications
    Route::get('/admin/notification-list/{filter}', [NotificationController::class, 'notificationList'])->name('notificationList');
    Route::get('/admin/isRead/{id}/{redirect_link}/{role}', [NotificationController::class, 'isRead'])->name('isRead');

    // Messages
    Route::get('/admin/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/admin/chat-selected/{contact}', [MessageController::class, 'chatSelected'])->name('chatSelected');
    Route::get('/admin/message-is-reacted/{id}', [MessageController::class, 'messageReacted'])->name('messageReacted');
    Route::post('/admin/message-send', [MessageController::class, 'messageSend'])->name('messageSend');
    Route::get('/admin/contacts', [MessageController::class, 'contacts'])->name('contacts');
    Route::post('/admin/message-new-send', [MessageController::class, 'messageNewSend'])->name('messageNewSend');
    Route::get('/admin/message-bubble/{receiver_name}', [MessageController::class, 'messageBubble'])->name('messageBubble');

    // Settings
    Route::post('/admin/dark-mode/{id}', [SettingController::class, 'darkMode'])->name('darkMode');
    Route::post('/admin/transitions/{id}', [SettingController::class, 'transitions'])->name('transitions');

    // Calendar Routes
    Route::get('/admin/calendar-move/{action}/{category}/{year}/{month}', [DashboardController::class, 'calendarMove'])->name('calendarMove');
    Route::get('/admin/date-view/{date}', [DashboardController::class, 'dateView'])->name('dateView');
    Route::get('/admin/date-custom', [DashboardController::class, 'dateCustom'])->name('dateCustom');
});

// Admin Role Routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users');
    Route::post('/admin/users-role-update', [UserController::class, 'roleUpdate'])->name('roleUpdate');
    Route::post('/admin/user-add', [UserController::class, 'create'])->name('userAdd');
    Route::post('/admin/user-delete/{id}', [UserController::class, 'delete'])->name('userDelete');
    Route::post('/admin/managed-categories/{category_id}', [RolePermissionController::class, 'managedCategoriesUpdate'])->name('managedCategoriesUpdate');

});

// Moderator and Admin Role Routes
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::post('/admin/category-add', [CategoryController::class, 'create'])->name('category-add');
    Route::post('/admin/transaction-decline/{id}', action: [TransactionController::class, 'decline'])->name('transactionDecline');
    Route::get('/admin/pending-approve/{id}', [TransactionController::class, 'approve'])->name('transactionApprove');
    //Category

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/admin/category-update/{category_id}', [CategoryController::class, 'update'])->name('categoryUpdate');

});

Route::middleware(['auth', 'role:superadmin|admin|staff'])->group(function () {

    Route::get('/admin/items', [ItemController::class, 'index'])->name('items');
    Route::get('/admin/items-filter', [ItemController::class, 'itemsFilter'])->name('itemsFilter');
    Route::get('/admin/item-search/{day}/{category_id}', [ItemController::class, 'search'])->name('itemSearch');
    Route::post('/admin/item-add', [ItemController::class, 'create'])->name('itemAdd');
    Route::put('/admin/item-update/{id}', [ItemController::class, 'update'])->name('itemUpdate');
    Route::get('admin/item-page-search/{category_id}/', [ItemController::class, 'itemSearch'])->name('adminItemSearch');
    // Transactions
    Route::post('/admin/transaction-create', [TransactionController::class, 'create'])->name('transaction-create');
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/admin/transactions-filter', [TransactionController::class, 'filter'])->name('transactionsFilter');

});


Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/admin/payments', [PaymentsController::class, 'index']);
    Route::get('/admin/payments', [PaymentsController::class, 'index'])->name('payments');
});
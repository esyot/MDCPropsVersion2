<?php
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ClaimPropertyController;
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RenteePropertyController;
use App\Http\Controllers\RenteeReservationController;
use App\Http\Controllers\RenteeTrackingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReturnPropertyController;
use App\Http\Controllers\UpdatesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RenteeCartController;
use App\Http\Controllers\RenteeHomeController;
use App\Http\Controllers\RenteeController;
use App\Http\Controllers\ContactController;



//Rentee Routes
Route::get('/', function () {
    return view('rentee.pages.welcome');
});


Route::get('/notifications', [UpdatesController::class, 'notifications']);

Route::get('/welcome', [RenteeHomeController::class, 'welcome'])->name('rentee.welcome');
Route::get('/rentee/properties/{category_id}/{rentee}', [RenteePropertyController::class, 'index'])->name('rentee.properties');
Route::get('/rentee/property/{id}', [RenteePropertyController::class, 'itemUnAvailableDates']);
Route::get('/rentee/items-filter/{category_id}/{rentee}', [RenteePropertyController::class, 'renteeItemsFilter'])->name('renteeItemsFilter');
Route::get('rentee/tracking', [RenteeTrackingController::class, 'index'])->name('tracking');
Route::get('/rentee/cart/{rentee}', [RenteeCartController::class, 'index'])->name('cart');
Route::get('/get-started', [RenteeController::class, 'create'])->name('rentee.start-reservation');
Route::get('/home/{rentee}', [RenteeHomeController::class, 'home'])->name('home');
Route::get('/rentee/cancel-order/{rentee}', [RenteeController::class, 'cancelOrder'])->name('cancelOrder');
Route::get('/rentee/add-to-cart/{rentee}/{property}', [RenteeCartController::class, 'addToCart'])->name('rentee.add-to-cart');
Route::get('/rentee/back-to-home/{rentee}', [RenteeHomeController::class, 'home'])->name('rentee.back-to-home');
Route::get('/rentee/checkout/{rentee}', [RenteeCartController::class, 'checkout'])->name('checkout');
Route::get('/rentee/remove-item-in-cart/{id}/{rentee}', [RenteeCartController::class, 'removeItemInCart'])->name('rentee.cart-remove-property');
Route::post('/rentee/create-transaction/{rentee}', [RenteeReservationController::class, 'reservationAdd'])->name('rentee.reservation-add');
Route::get('/rentee/track-transaction', [RenteeTrackingController::class, 'track'])->name('transactionTrack');
Route::get('/rentee/tracking-page', [RenteeTrackingController::class, 'index'])->name('tracking');
Route::get('/rentee/tracking', [RenteeTrackingController::class, 'fetch']);
Route::get('/rentee/transaction-done/{reservation}', [RenteeReservationController::class, 'reservationComplete'])->name('rentee.reservation-complete');
Route::get('/rentee/reservation-cancel/{tracking_code}', [RenteeReservationController::class, 'reservationCancel'])->name('rentee.reservation-cancel');


// Public Routes
Route::get('/admin/login', [LoginController::class, 'index'])->name('loginPage');
Route::post('/admin.login', [AuthController::class, 'login'])->name('login');
Route::get('test', function () {
    return view('test');
});


//Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout');

    //Profile Management
    Route::get('/admin/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/admin/profile/update/{id}', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('/admin/password/update/{id}', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');

    //Notifications
    Route::get('/admin/notification-list/{filter}', [NotificationController::class, 'notificationList'])->name('notificationList');
    Route::get('/admin/isRead/{id}/{redirect_link}/{role}', [NotificationController::class, 'isRead'])->name('isRead');
    Route::get('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');

    //Settings
    Route::post('/admin/dark-mode/{id}', [SettingController::class, 'darkMode'])->name('darkMode');
    Route::post('/admin/transitions/{id}', [SettingController::class, 'transitions'])->name('transitions');

    //Messages Routes
    Route::get('/admin/contacts-search', [ContactController::class, 'search'])->name('searchContact');
    Route::get('/admin/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/admin/chat-selected/{contact}', [MessageController::class, 'chatSelected'])->name('chatSelected');
    Route::get('/admin/message-is-reacted/{id}', [MessageController::class, 'messageReacted'])->name('messageReacted');
    Route::post('/admin/message-send', [MessageController::class, 'messageSend'])->name('messageSend');
    Route::get('/admin/contacts', [MessageController::class, 'contacts'])->name('contacts');
    Route::post('/admin/message-new-send', [MessageController::class, 'messageNewSend'])->name('messageNewSend');
    Route::get('/admin/message-bubble/{receiver_id}', [MessageController::class, 'messageBubble'])->name('messageBubble');

});

// Admin Role Routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    //User page
    Route::get('/admin/users', [UserController::class, 'index'])->name('users');
    Route::post('/admin/users-role-update', [UserController::class, 'roleUpdate'])->name('roleUpdate');
    Route::post('/admin/user-add', [UserController::class, 'create'])->name('userAdd');
    Route::post('/admin/user-delete/{id}', [UserController::class, 'delete'])->name('userDelete');
    Route::post('/admin/managed-categories/{category_id}', [RolePermissionController::class, 'managedCategoriesUpdate'])->name('managedCategoriesUpdate');
    Route::get('/admin/users-filter', [UserController::class, 'filter'])->name('admin.users-search');
});

// Moderator and Admin Role Routes
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    //Transaction Approval Routes
    Route::post('/admin/category-add', [CategoryController::class, 'create'])->name('admin.category-add');
    Route::post('/admin/reservation-decline/{id}', action: [ReservationController::class, 'decline'])->name('admin.reservation-decline');
    Route::get('/admin/reservation-pending-approve/{id}', [ReservationController::class, 'approve'])->name('admin.reservation-approve');

    //categories Update
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/admin/category-update/{category_id}', [CategoryController::class, 'update'])->name('admin.category-update');

    //Analytics Page
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics-index');
    Route::get('/admin/analytics-charts-custom-year', [AnalyticsController::class, 'index'])->name('admin.analytics-charts-custom-year');
    Route::post('/admin/analytics-export-to-pdf', [ExportPDFController::class, 'analyticsExportPDF'])->name('admin.analytics-export-to-pdf');

    //Reservations


    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('admin.reservations');
    Route::get('/admin/reservations-filter', [ReservationController::class, 'filter'])->name('admin.reservations-filter');
});

Route::middleware(['auth', 'role:superadmin|admin|staff'])->group(function () {

    //Items Routes
    Route::get('/admin/properties', [PropertyController::class, 'index'])->name('admin.properties');
    Route::get('/admin/properties-filter', [PropertyController::class, 'propertiesFilter'])->name('admin.properties-filter');
    Route::get('/admin/property-search/{day}/{category_id}', [PropertyController::class, 'search'])->name('admin.property-search');
    Route::post('/admin/property-add', [PropertyController::class, 'create'])->name('admin.property-add');
    Route::put('/admin/property-update/{id}', [PropertyController::class, 'update'])->name('admin.property-update');
    Route::get('/admin/property-page-search/{category_id}/', [PropertyController::class, 'propertySearch'])->name('admin.property-page-search');

    // Transactions Routes
    Route::post('/admin/reservation-add', [ReservationController::class, 'create'])->name('admin.reservation-add');

    //Item Return Routes
    Route::get('/admin/search-reservation-for-return', [ReturnPropertyController::class, 'searchReservationForReturn'])->name('admin.search-reservation-for-return');
    Route::get('/admin/reseved-items-returned/{transaction_id}', [ReturnPropertyController::class, 'reservedItemsReturned'])->name('admin.reserved-items-returned');
    Route::get('/admin/reserved-items-to-return/{transaction_id}', [ReturnPropertyController::class, 'reservedItemsToReturn'])->name('admin.reserved-items-to-return');
    Route::get('/admin/return-items', [ReturnPropertyController::class, 'index'])->name('admin.return-items');

    //Item Claim Routes
    Route::get('/admin/search-reservation-for-claim', [ClaimPropertyController::class, 'searchReservationForClaim'])->name('admin.search-reservation-for-claim');
    Route::get('/admin/reseved-items-claimed/{transaction_id}', [ClaimPropertyController::class, 'reservedItemsClaimed'])->name('admin.reserved-items-claimed');
    Route::get('/admin/reserved-items-to-claim/{transaction_id}', [ClaimPropertyController::class, 'reservedItemsToClaim'])->name('admin.reserved-items-to-claim');
    Route::get('/admin/claim-items', [ClaimPropertyController::class, 'index'])->name('admin.claim-items');


    //Calendar Routes
    Route::get('/admin/calendar-move/{action}/{category}/{year}/{month}', [DashboardController::class, 'calendarMove'])->name('admin.calendar-move');
    Route::get('/admin/date-custom', [DashboardController::class, 'dateCustom'])->name('admin.date-custom');
    Route::get('admin/calendar-select-month/{year}/{month}/{category}', [CalendarController::class, 'selectMonth'])->name('admin.select-month');
    Route::get('/admin/calendar-day-view/{date}/{category_id}', [CalendarController::class, 'calendarDayView'])->name('admin.calendar-day-view');

});


Route::middleware(['auth', 'role:cashier'])->group(function () {

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

});



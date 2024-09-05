<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/date-view/{date}', [DashboardController::class, 'dateView'])->name('dateView');
Route::get('/date-custom', [DashboardController::class, 'dateCustom'])->name('dateCustom');

Route::post('/transaction-create', [DashboardController::class, 'transactionAdd'])->name('transaction-create');

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
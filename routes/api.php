<?php

use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\{TransactionController, UserController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
	Route::prefix('users')->group(function () {
		Route::post('', [UserController::class, 'storeUser']);
		Route::put('', [UserController::class, 'updateUser']);
		Route::get('{user_id}', [UserController::class, 'getUserById']);
		Route::delete('{user_id}', [UserController::class, 'deleteUser']);
	});
	Route::prefix('transactions')->group(function () {
		Route::post('', [TransactionController::class, 'storeTransaction']);
		Route::get('{transaction_id}', [TransactionController::class, 'getTransactionById']);
		Route::post('refund/{transaction_id}', [TransactionController::class, 'refundTransaction']);
	});
	Route::get('logs', [LogViewerController::class, 'index']);
});
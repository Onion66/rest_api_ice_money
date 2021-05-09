<?php

use App\Http\Controllers\API\CategoryTransactionController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('transactions', [TransactionController::class, 'index']);
Route::get('transactions/{id}', [TransactionController::class, 'show']);

Route::get('category-transactions', [CategoryTransactionController::class, 'index']);
Route::get('category-transactions/{id}', [CategoryTransactionController::class, 'show']);
Route::post('category-transactions/add', [CategoryTransactionController::class, 'store']);
Route::post('category-transactions/update', [CategoryTransactionController::class, 'update']);
Route::post('category-transactions/delete', [CategoryTransactionController::class, 'destroy']);

Route::get('payment-methods', [PaymentMethodController::class, 'index']);
Route::get('payment-methods/{id}', [PaymentMethodController::class, 'show']);

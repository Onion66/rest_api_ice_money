<?php

use App\Http\Controllers\API\AuthController;
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

Route::post('auth/login', [AuthController::class, 'login']);

Route::post('auth/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user-data', [AuthController::class, 'userData']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/update', [AuthController::class, 'updateUser']);

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{id}', [TransactionController::class, 'show']);

    Route::get('category-transactions', [CategoryTransactionController::class, 'index']);
    Route::get('category-transactions/{id}', [CategoryTransactionController::class, 'show']);
    Route::post('category-transactions/add', [CategoryTransactionController::class, 'store']);
    Route::post('category-transactions/update', [CategoryTransactionController::class, 'update']);
    Route::post('category-transactions/delete', [CategoryTransactionController::class, 'destroy']);

    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::get('payment-methods/{id}', [PaymentMethodController::class, 'show']);
    Route::post('payment-methods/add', [PaymentMethodController::class, 'store']);
    Route::post('payment-methods/update', [PaymentMethodController::class, 'update']);
    Route::post('payment-methods/delete', [PaymentMethodController::class, 'destroy']);
});

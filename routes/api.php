<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('customers')->group(function(){
    Route::get('/', [CustomerController::class, "fetchAll"]);
    Route::get('{customerId}', [CustomerController::class, "fetchOne"]);

    Route::post('/', [CustomerController::class, "store"]);
    Route::post('{customerId}/update-customer', [CustomerController::class, "update"]);
});

Route::prefix('admins')->group(function(){
    Route::get('{adminId}', [AdminController::class, "fetchOne"]);

    Route::post('/', [AdminController::class, "store"]);
});

Route::prefix('roles')->group(function(){
    Route::get('/', [RoleController::class, "fetchAll"]);
    Route::get('{roleId}', [RoleController::class, "fetchOne"]);

    Route::post('/', [RoleController::class, "store"]);
});

Route::prefix('transactions')->group(function(){
    Route::get('/', [TransactionController::class, "fetchAll"]);
    Route::get('{transactionsId}', [TransactionController::class, "fetchOne"]);

    Route::post('process', [TransactionController::class, "process"]);
});
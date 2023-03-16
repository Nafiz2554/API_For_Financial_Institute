<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

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

//to show all customers
Route::get('customers',[CustomerController::class,'index']);

//to create a customer
Route::post('customers',[CustomerController::class,'store']);

//to show individual customer by id
Route::get('customers/{id}',[CustomerController::class,'show']);

//to delete a customer by id
Route::delete('customers/{id}/delete',[CustomerController::class,'destroy']);

// Create account for a customer
Route::post('customers/{customer}/accounts', [AccountController::class,'store']);

//Show individual account details
Route::get('account/details/{id}',[AccountController::class,'show']);

//show individual account balance
Route::get('account/balance/{id}',[AccountController::class,'showBalance']);

//to transfer amount
Route::post('transfer/amount',[TransactionController::class,'transfer']);

//show all transaction
Route::get('transactions',[TransactionController::class, 'showAllTransaction']);

//show individual transaction by id
Route::get('transactions/{id}',[TransactionController::class, 'showOneTransaction']);

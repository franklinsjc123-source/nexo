<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login',[AuthController::class,"login"])->name('login');
Route::post('/register',[AuthController::class,"register"])->name('register');
Route::post('/checkOTP',[AuthController::class,"checkOTP"])->name('checkOTP');


Route::post('cart/add', [CartController::class, 'addToCart']);
Route::get('cart', [CartController::class, 'viewCart']);
Route::put('cart/update', [CartController::class, 'updateCartItem']);
Route::delete('cart/remove/{item_id}', [CartController::class, 'removeCartItem']);
Route::delete('cart/clear', [CartController::class, 'clearCart']);

Route::get('/getAllCategory',[HomeController::class,"getAllCategory"])->name('getAllCategory');
Route::get('/getAllShopsByCategory',[HomeController::class,"getAllShopsByCategory"])->name('getAllShopsByCategory');

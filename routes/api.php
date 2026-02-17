<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AdressController;

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

Route::post('add-cart', [CartController::class, 'addToCart'])->name('add-cart');
Route::get('view-cart', [CartController::class, 'viewCart'])->name('view-cart');
Route::post('update-cart', [CartController::class, 'updateCartItem'])->name('update-cart');
Route::get('remove-cart-item', [CartController::class, 'removeCartItem'])->name('remove-cart-item');
Route::get('clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');

Route::post('add-address', [AdressController::class, 'addAddress'])->name('add-address');



Route::get('/getUserDetails',[AuthController::class,"getUserDetails"])->name('getUserDetails');
Route::get('/getAllCategory',[HomeController::class,"getAllCategory"])->name('getAllCategory');
Route::get('/getAllShopsByCategory',[HomeController::class,"getAllShopsByCategory"])->name('getAllShopsByCategory');
Route::get('/getAllProductsByShop',[HomeController::class,"getAllProductsByShop"])->name('getAllProductsByShop');
Route::get('/getAllSlider',[HomeController::class,"getAllSlider"])->name('getAllSlider');

Route::get('/home-page',[HomeController::class,"getHomePageDetails"])->name('home-page');



Route::post('/placeDirectOrder',[OrderController::class,"placeDirectOrder"])->name('placeDirectOrder');




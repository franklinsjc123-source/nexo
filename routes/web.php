<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CommonController;
use App\Http\Controllers\Backend\PincodeController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ShopController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductUploadController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DeliveryPersonController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OfferController;


use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class, 'index'])->name('login');
Route::post("authLogin", [AuthController::class, 'authLogin'])->name('authLogin');
  Route::get('/refresh-session', function () {
    return response()->json(['status' => 'session refreshed']);
    });




Route::middleware('auth.request')->group(function () {

    Route::get("dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get("logout", [AuthController::class, 'logout'])->name('logout');


    Route::get('profile', [CommonController::class, 'profile'])->name('profile');
    Route::post('/save-profile', [CommonController::class, "saveProfile"])->name('save-profile');
    Route::post('/change-password', [CommonController::class, "changePassword"])->name('change-password');
    Route::post('/updateCommonStatus', [CommonController::class, "updateCommonStatus"])->name('updateCommonStatus');
    Route::post('/commonDelete', [CommonController::class, "commonDelete"])->name('commonDelete');
    Route::post('/checkExist', [CommonController::class, "checkExist"])->name('checkExist');



    //pincode
    Route::get('pincode', [PincodeController::class, 'pincode'])->name('pincode');
    Route::get('addPincode/{id?}', [PincodeController::class, 'addPincode'])->name('addPincode');
    Route::post('storeUpdatePincode', [PincodeController::class, 'storeUpdatePincode'])->name('storeUpdatePincode');


    //unit
    Route::get('unit', [UnitController::class, 'unit'])->name('unit');
    Route::get('addUnit/{id?}', [UnitController::class, 'addUnit'])->name('addUnit');
    Route::post('storeUpdateUnit', [UnitController::class, 'storeUpdateUnit'])->name('storeUpdateUnit');


    //Usesr Management
    Route::get("users", [UserController::class, 'users'])->name('users');
    Route::get('addUser/{id?}', [UserController::class, 'addUser'])->name('addUser');
    Route::post('storeUpdateUser', [UserController::class, 'storeUpdateUser'])->name('storeUpdateUser');


    // Slider
    Route::get('slider', [SliderController::class, 'slider'])->name('slider');
    Route::get('addSlider/{id?}', [SliderController::class, 'addSlider'])->name('addSlider');
    Route::post('storeUpdateSlider', [SliderController::class, 'storeUpdateSlider'])->name('storeUpdateSlider');


    //company
    Route::get('company', [CompanyController::class, 'company'])->name('company');
    Route::get('addCompany/{id?}', [CompanyController::class, 'addCompany'])->name('addCompany');
    Route::post('storeUpdateCompany', [CompanyController::class, 'storeUpdateCompany'])->name('storeUpdateCompany');

    //permission
    Route::match(['get', 'post'], 'permission', [PermissionController::class, 'permission'])->name('permission');
    Route::post('/storeUpdatePermission', [PermissionController::class, "storeUpdatePermission"])->name('storeUpdatePermission');
    Route::match(['get', 'post'], '/assign-permission', [PermissionController::class, "assignPermission"])->name('assign-permission');
    Route::get('/getPermission/{id}', [PermissionController::class, "getPermission"])->name('getPermission');
    Route::post('updatePermission', [PermissionController::class, "updatePermission"])->name('updatePermission');
    Route::get('addPermission/{id?}', [PermissionController::class, 'addPermission'])->name('addPermission');

    // Category
    Route::get('category', [CategoryController::class, 'category'])->name('category');
    Route::get('addCategory/{id?}', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::post('storeUpdateCategory', [CategoryController::class, 'storeUpdateCategory'])->name('storeUpdateCategory');

    // shop
    Route::get('shop', [ShopController::class, 'shop'])->name('shop');
    Route::get('addShop/{id?}', [ShopController::class, 'addShop'])->name('addShop');
    Route::post('storeUpdateShop', [ShopController::class, 'storeUpdateShop'])->name('storeUpdateShop');


    // product
    Route::get('product', [ProductController::class, 'product'])->name('product');
    Route::get('addProduct/{id?}', [ProductController::class, 'addProduct'])->name('addProduct');
    Route::post('storeUpdateProduct', [ProductController::class, 'storeUpdateProduct'])->name('storeUpdateProduct');
    Route::get('get-shops-by-category', [ProductController::class, 'getShopsByCategory'])->name('getShopsByCategory');

    // product upload
    Route::get('sample-excel', [ProductUploadController::class, 'exportExcel'])->name('sample-excel');
    // Route::get('sample-csv', [ProductUploadController::class, 'exportCSV'])->name('sample-csv');
    Route::get('product-upload', [ProductUploadController::class, 'productUpload'])->name('product-upload');
    Route::post('storeProductUpload', [ProductUploadController::class, 'storeProductUpload'])->name('storeProductUpload');


    //Customer Management
    Route::get("customers", [CustomerController::class, 'customers'])->name('customers');

    //orders Management
    Route::get("orders", [OrderController::class, 'orders'])->name('orders');


    //Delivery Person Management
    Route::get("deliveryPerson", [DeliveryPersonController::class, 'deliveryPerson'])->name('deliveryPerson');
    Route::get('addDeliveryPerson/{id?}', [DeliveryPersonController::class, 'addDeliveryPerson'])->name('addDeliveryPerson');
    Route::post('storeUpdateDeliveryPerson', [DeliveryPersonController::class, 'storeUpdateDeliveryPerson'])->name('storeUpdateDeliveryPerson');


    // offers
    Route::get('offers', [OfferController::class, 'offers'])->name('offers');
    Route::get('addOffer/{id?}', [OfferController::class, 'addOffer'])->name('addOffer');
    Route::post('storeUpdateOffer', [OfferController::class, 'storeUpdateOffer'])->name('storeUpdateOffer');

});

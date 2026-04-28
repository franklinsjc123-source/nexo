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
use App\Http\Controllers\Backend\DirectOrderController;
use App\Http\Controllers\Backend\OfferController;
use App\Http\Controllers\Backend\ReferralController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\TaxController;
use App\Http\Controllers\Backend\PushNotificationController;



use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class, 'index'])->name('login');
Route::post("authLogin", [AuthController::class, 'authLogin'])->name('authLogin');
Route::get('/refresh-session', function () {
    return response()->json(['status' => 'session refreshed']);
});

Route::get("privacy-policy", [DashboardController::class, 'privacy_policy'])->name('privacy-policy');
Route::get("account-deletion", [DashboardController::class, 'account_deletion'])->name('account-deletion');
Route::post("post-account-deletion", [DashboardController::class, 'post_account_deletion'])->name('post-account-deletion');

Route::get('/razorpay-test', function() {
    $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $order = $api->order->create([
        'receipt' => 'test_receipt_' . time(),
        'amount' => 100 * 100, // INR 100
        'currency' => 'INR'
    ]);
    
    $rzp_id = $order['id'];
    $key = env('RAZORPAY_KEY');

    return view('razorpay-test', compact('rzp_id', 'key'));
});

Route::post('/razorpay-test-callback', function(\Illuminate\Http\Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'Payment received in test callback.',
        'data' => $request->all()
    ]);
})->name('razorpay-test-callback')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware('auth.request')->group(function () {

    Route::get("dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get("logout", [AuthController::class, 'logout'])->name('logout');
    Route::post('check-new-orders', [DashboardController::class, 'checkNewOrders'])->name('checkNewOrders');


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

    //tax
    Route::get("tax", [TaxController::class, 'tax'])->name('tax');
    Route::get('addTax/{id?}', [TaxController::class, 'addTax'])->name('addTax');
    Route::post('storeUpdateTax', [TaxController::class, 'storeUpdateTax'])->name('storeUpdateTax');


    //Usesr Management
    Route::get("users", [UserController::class, 'users'])->name('users');
    Route::get('addUser/{id?}', [UserController::class, 'addUser'])->name('addUser');
    Route::post('storeUpdateUser', [UserController::class, 'storeUpdateUser'])->name('storeUpdateUser');


    //Refferar
    Route::get("referral", [ReferralController::class, 'referral'])->name('referral');
    Route::get('addReferral/{id?}', [ReferralController::class, 'addReferral'])->name('addReferral');
    Route::post('storeUpdateReferral', [ReferralController::class, 'storeUpdateReferral'])->name('storeUpdateReferral');

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
    Route::post('orders-status-update', [OrderController::class, 'updateOrderStatus'])->name('orders-status-update');
    Route::post('/get-order-items', [OrderController::class, 'getOrderItems']);


    //direct orders Management
    Route::get("direct-orders", [DirectOrderController::class, 'directOrders'])->name('direct-orders');
    Route::match(['get', 'post'], "direct-order-abstract", [DirectOrderController::class, 'directOrdersAbstract'])->name('direct-order-abstract');
    Route::post('/abstract/download', [DirectOrderController::class, 'storeDirectOrdersAbstract'])->name('abstract.download');
    Route::get("addDirectOrderBill/{id}", [DirectOrderController::class, 'addDirectOrderBill'])->name('addDirectOrderBill');
    Route::post('direct-orders-status-update', [DirectOrderController::class, 'updateOrderStatus'])->name('direct-orders-status-update');
    Route::post('storeUpdateDirectOrder', [DirectOrderController::class, 'storeUpdateDirectOrder'])->name('storeUpdateDirectOrder');


    //Delivery Person Management
    Route::get("deliveryPerson", [DeliveryPersonController::class, 'deliveryPerson'])->name('deliveryPerson');
    Route::get('addDeliveryPerson/{id?}', [DeliveryPersonController::class, 'addDeliveryPerson'])->name('addDeliveryPerson');
    Route::post('storeUpdateDeliveryPerson', [DeliveryPersonController::class, 'storeUpdateDeliveryPerson'])->name('storeUpdateDeliveryPerson');


    // offers
    Route::get('offers', [OfferController::class, 'offers'])->name('offers');
    Route::get('addOffer/{id?}', [OfferController::class, 'addOffer'])->name('addOffer');
    Route::post('storeUpdateOffer', [OfferController::class, 'storeUpdateOffer'])->name('storeUpdateOffer');

    // Push Notification
    Route::get('push-notification', [PushNotificationController::class, 'index'])->name('push-notification');
    Route::post('send-push-notification', [PushNotificationController::class, 'sendPushNotification'])->name('send-push-notification');

    //Report Management
    Route::match(['get', 'post'], "orders-report", [ReportController::class, 'ordersReport'])->name('orders-report');
    Route::match(['get', 'post'], "direct-orders-report", [ReportController::class, 'directOrdersReport'])->name('direct-orders-report');
});

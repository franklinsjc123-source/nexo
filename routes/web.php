<?php

use App\Http\Controllers\Backend\AuthController;

use App\Http\Controllers\Backend\CommonController;

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\VehicleController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\RouteController;
use App\Http\Controllers\Backend\DriverController;
use App\Http\Controllers\Backend\ShiftController;
use App\Http\Controllers\Backend\AttachmentVehicleController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\OilServiceController;
use App\Http\Controllers\Backend\GreaseController;
use App\Http\Controllers\Backend\TyreController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ReportsController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\InsuranceController;
use App\Http\Controllers\Backend\VehicleDetailController;
use App\Http\Controllers\Backend\StaffAttendanceController;
use App\Http\Controllers\Backend\DieselEntryController;
use App\Http\Controllers\Backend\VehiclePermitController;
use App\Http\Controllers\Backend\DriverAttendanceController;
use App\Http\Controllers\Backend\LogBookEntryController;
use App\Http\Controllers\Backend\AdvanceController;
use App\Http\Controllers\Backend\TripBillController;

use App\Http\Controllers\Backend\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class, 'index'])->name('login');
Route::post("authLogin", [AuthController::class, 'authLogin'])->name('authLogin');
  Route::get('/refresh-session', function () {
    return response()->json(['status' => 'session refreshed']);
    });




Route::middleware('auth.request')->group(function () {

    Route::get("dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get("logout", [AuthController::class, 'logout'])->name('logout');
    Route::get("blank", [DashboardController::class, 'blank'])->name('blank');
    Route::get("users", [UserController::class, 'users'])->name('users');


    Route::get('profile', [CommonController::class, 'profile'])->name('profile');

    Route::post('/save-profile', [CommonController::class, "saveProfile"])->name('save-profile');
    Route::post('/change-password', [CommonController::class, "changePassword"])->name('change-password');

    Route::post('/updateCommonStatus', [CommonController::class, "updateCommonStatus"])->name('updateCommonStatus');
    Route::post('/commonDelete', [CommonController::class, "commonDelete"])->name('commonDelete');
    Route::post('/checkExist', [CommonController::class, "checkExist"])->name('checkExist');


    Route::get('/fetch/zone-based-data', [CommonController::class, 'getZoneBasedData']);
    Route::get('/fetch/company-based-data', [CommonController::class, 'getCompanyBasedData']);
    Route::get('/fetch/vehicle-based-data', [CommonController::class, 'getVehicleBasedData']);


    Route::match(['get', 'post'], '/staffAttendance_report', [ReportsController::class, "staffAttendance_report"])->name('staffAttendance_report');
    Route::match(['get', 'post'], '/driverAttendance_report', [ReportsController::class, "driverAttendance_report"])->name('driverAttendance_report');
    Route::match(['get', 'post'], '/dieselEntry_report', [ReportsController::class, "dieselEntry_report"])->name('dieselEntry_report');
    Route::match(['get', 'post'], '/daywise_report', [ReportsController::class, "daywise_report"])->name('daywise_report');
    Route::match(['get', 'post'], '/Driverwise_RunningKM_Details', [ReportsController::class, "Driverwise_RunningKM_Details"])->name('Driverwise_RunningKM_Details');
    Route::match(['get', 'post'], '/Companywise_Running_KM', [ReportsController::class, "Companywise_Running_KM"])->name('Companywise_Running_KM');
    Route::match(['get', 'post'], '/Vehiclewise_km', [ReportsController::class, "Vehiclewise_km"])->name('Vehiclewise_km');
    Route::match(['get', 'post'], '/MF_Vehilce', [ReportsController::class, "MF_vehilce"])->name('MF_Vehilce');




    Route::match(['get', 'post'], '/fc_entry', [VehicleDetailController::class, "fc_entry"])->name('fc_entry');
    Route::get('addFCentry/{id?}', [VehicleDetailController::class, 'addFCentry'])->name('addFCentry');
    Route::post('/storeUpdateFCentry', [VehicleDetailController::class, 'storeUpdateFCentry'])->name('storeUpdateFCentry');

    Route::match(['get', 'post'], '/insurance_entry', [InsuranceController::class, "insurance_entry"])->name('insurance_entry');
    Route::get('addInsurance/{id?}', [InsuranceController::class, 'addInsurance'])->name('addInsurance');
    Route::post('/storeUpdateInsurance', [InsuranceController::class, 'storeUpdateInsurance'])->name('storeUpdateInsurance');

    Route::match(['get', 'post'], '/vehicle_permit', [VehiclePermitController::class, "vehicle_permit"])->name('vehicle_permit');
    Route::get('addPermit/{id?}', [VehiclePermitController::class, 'addPermit'])->name('addPermit');
    Route::post('/storeUpdatePermit', [VehiclePermitController::class, 'storeUpdatePermit'])->name('storeUpdatePermit');

    Route::match(['get', 'post'], '/vehicle_status', [VehicleDetailController::class, "vehicle_status"])->name('vehicle_status');

    //naveen
    Route::match(['get', 'post'], '/staff-attendance', [StaffAttendanceController::class, "staffAttendance"])->name('staff-attendance');
    Route::post('check-staff-attendance-date', [StaffAttendanceController::class, 'checkDate'])->name('check.staff.attendance.date');
    Route::get('addstaffAttendance/{id?}', [StaffAttendanceController::class, 'addstaffAttendance'])->name('addstaffAttendance');
    Route::post('/storeUpdStaffAttendance', [StaffAttendanceController::class, 'storeUpdStaffAttendance'])->name('storeUpdStaffAttendance');

    Route::match(['get', 'post'], '/diesel-entry', [DieselEntryController::class, "dieselEntry"])->name('diesel-entry');

    Route::get('addDieselEntry/{id?}', [DieselEntryController::class, 'addDieselEntry'])->name('addDieselEntry');
    Route::post('/storeUpdDieselEntry', [DieselEntryController::class, 'storeUpdDieselEntry'])->name('storeUpdDieselEntry');
    Route::get('/get-vehicle-previous-km', [DieselEntryController::class, 'getPreviousKm'])->name('get-vehicle-previous-km');

    Route::match(['get', 'post'], '/newdiesel-entry', [DieselEntryController::class, "newdieselEntry"])->name('newdiesel-entry');
    Route::get('diesel-entry/filter', [DieselEntryController::class, 'filterByDateVehicle'])
    ->name('diesel.entry.filter');





    Route::get('get-driver-details/{id}', [DriverAttendanceController::class, 'getDriverDetails'])->name('get.driver.details');

    Route::match(['get', 'post'], '/logbook-entry', [ReportsController::class, "logbookEntry"])->name('logbook-entry');

    Route::match(['get', 'post'], '/driver-attendance', [DriverAttendanceController::class, "driverAttendance"])->name('driver-attendance');
    Route::post('check-driver-attendance-date', [DriverAttendanceController::class, 'checkDate'])->name('check.driver.attendance.date');
    Route::get('addDriverAttendance/{id?}', [DriverAttendanceController::class, 'addDriverAttendance'])->name('addDriverAttendance');
    Route::post('/storeUpdDriverAttendance', [DriverAttendanceController::class, 'storeUpdDriverAttendance'])->name('storeUpdDriverAttendance');

    Route::get('get-drivers-by-zonal', [DriverAttendanceController::class, 'getDriversByZonal'])
    ->name('get.drivers.by.zonal');

    Route::match(['get', 'post'], '/addedit-log', [ReportsController::class, "addeditLog"])->name('addedit-log');


    //thendral
    Route::get('vehicle', [VehicleController ::class, 'vehicle'])->name('vehicle');
    Route::get('logBookAbstract', [VehicleController ::class, 'logBookAbstract'])->name('logBookAbstract');
    Route::get('addVehicle/{id?}', [VehicleController ::class, 'addVehicle'])->name('addVehicle');
    Route::post('storeUpdateVehicle', [VehicleController::class, 'storeUpdateVehicle'])->name('storeUpdateVehicle');
    Route::post('/get-vehicle-details', [VehicleController::class, 'getVehicleDetails'])
     ->name('get.vehicle.details');

    Route::get('driver', [DriverController ::class, 'driver'])->name('driver');
    Route::get('addDriver/{id?}', [DriverController ::class, 'addDriver'])->name('addDriver');
    Route::post('storeUpdateDriver', [DriverController::class, 'storeUpdateDriver'])->name('storeUpdateDriver');


    Route::match(['get', 'post'],'driver-advance', [AdvanceController ::class, 'driverAdvance'])->name('driver-advance');
    Route::get('addAdvance/{id?}', [AdvanceController ::class, 'addAdvance'])->name('addAdvance');
    Route::post('storeUpdateDriverAdvance', [AdvanceController::class, 'storeUpdateDriverAdvance'])->name('storeUpdateDriverAdvance');
    Route::post('get-driver-details', [AdvanceController::class, 'getDriverDetails']);

    Route::get('company', [CompanyController ::class, 'company'])->name('company');
    Route::get('addCompany/{id?}', [CompanyController ::class, 'addCompany'])->name('addCompany');
    Route::post('storeUpdateCompany', [CompanyController::class, 'storeUpdateCompany'])->name('storeUpdateCompany');

    Route::get('route', [RouteController ::class, 'route'])->name('route');
    Route::get('addRoute/{id?}', [RouteController ::class, 'addRoute'])->name('addRoute');
    Route::post('storeUpdateRoute', [RouteController::class, 'storeUpdateRoute'])->name('storeUpdateRoute');
    Route::post('/get-company-routecode', [RouteController::class, 'getCompanyRouteCode'])->name('get.company.routecode');

        Route::get('shift', [ShiftController ::class, 'shift'])->name('shift');
    Route::get('addShift/{id?}', [ShiftController ::class, 'addShift'])->name('addShift');
    Route::post('storeUpdateShift', [ShiftController::class, 'storeUpdateShift'])->name('storeUpdateShift');

    Route::get('attachmentVehicle', [AttachmentVehicleController ::class, 'attachmentVehicle'])->name('attachmentVehicle');
    Route::get('addAttachmentVehicle/{id?}', [AttachmentVehicleController ::class, 'addAttachmentVehicle'])->name('addAttachmentVehicle');
    Route::post('storeUpdateAttachmentVehicle', [AttachmentVehicleController::class, 'storeUpdateAttachmentVehicle'])->name('storeUpdateAttachmentVehicle');

     Route::get('staff', [StaffController ::class, 'staff'])->name('staff');
    Route::get('addStaff/{id?}', [StaffController ::class, 'addStaff'])->name('addStaff');
    Route::post('storeUpdateStaff', [StaffController::class, 'storeUpdateStaff'])->name('storeUpdateStaff');

    Route::get('oilService', [OilServiceController ::class, 'oilService'])->name('oilService');
    Route::get('addOilService/{id?}', [OilServiceController ::class, 'addOilService'])->name('addOilService');
    Route::post('storeUpdateOilService', [OilServiceController::class, 'storeUpdateOilService'])->name('storeUpdateOilService');

    Route::get('grease', [GreaseController ::class, 'grease'])->name('grease');
    Route::get('addGrease/{id?}', [GreaseController ::class, 'addGrease'])->name('addGrease');
    Route::post('storeUpdateGrease', [GreaseController::class, 'storeUpdateGrease'])->name('storeUpdateGrease');


    Route::get('tyre', [TyreController ::class, 'tyre'])->name('tyre');
    Route::get('addTyre/{id?}', [TyreController ::class, 'addTyre'])->name('addTyre');
    Route::post('storeUpdateTyre', [TyreController::class, 'storeUpdateTyre'])->name('storeUpdateTyre');

    Route::get('companySetting',[SettingController ::class, 'companySetting'])->name('companySetting');
    Route::post('storecompanySetting', [SettingController::class, 'storecompanySetting'])->name('storecompanySetting');


        Route::get('addLogBookEntry/{id?}', [LogBookEntryController::class, 'addLogBookEntry'])->name('addLogBookEntry');
        Route::post('storeLogBook/{id?}', [LogBookEntryController::class, 'storeLogBook'])->name('storeLogBook');
        Route::get('/get-vehicle-starting-km', [LogBookEntryController::class, 'getStartingKm'])->name('get-vehicle-starting-km');
        Route::get('/get-driver-attendance-data', [LogBookEntryController::class, 'getDriverAttendanceData'])->name('get-driver-attendance-data');
        Route::get('/get-route-fixed-km', [LogBookEntryController::class, 'getRouteFixedKm'])->name('get.route.fixedkm');

        Route::get('/getDriversByZone/{zoneId}', [DriverController::class, 'getDriversByZone'])->name('getDriversByZone');

        // Route::get('/logbook/list', [LogBookEntryController::class, 'logbookList'])->name('logbook.list');
        // Route::get('logbook/by-vehicle/{id?}', [LogBookEntryController::class, 'getByVehicle'])->name('logbook.byVehicle');

        Route::get('getByVehicle', [LogBookEntryController::class, 'getByVehicle'])->name('getByVehicle');

        Route::post('/logbook/bulk-update-driver',[LogBookEntryController::class, 'bulkUpdateDriver'])->name('logbook.bulk.update.driver');
        Route::post('/logbook/bulk-update',[LogBookEntryController::class, 'bulkUpdateSingle'])->name('logbook.bulk.update.single');
            Route::get('getZonalDriver', [RouteController::class, 'getZonalDriver'])->name('getZonalDriver');
            Route::get('getDriverBasedData', [DriverAttendanceController::class, 'getDriverBasedData'])->name('getDriverBasedData');
            Route::get('getCompanyBasedVehicleData', [DriverAttendanceController::class, 'getCompanyBasedVehicleData'])->name('getCompanyBasedVehicleData');

    Route::post('/abstract/download', [TripBillController::class, 'storeAbstract'])->name('abstract.download');

});

<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeStatusController;
use App\Http\Controllers\MenstruationLeaveController;
use App\Http\Controllers\OrganizationStructureController;
use App\Http\Controllers\PermissionLeaveOfficeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RegionController;
use App\Models\PermissionLeaveOffice;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/user', function() {
    $pageTitle = "Template User";
    return view('user', compact('pageTitle'));
})->name('template.user');
Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/dashboard', function() {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::get('/password-reset', function() {
    return 'password reset';
})->name('password.reset');
Route::get('/password-email', function() {
    return 'password email';
})->name('password.email');
Route::get('/password-request', function() {
    return 'password request';
})->name('password.request');

Route::middleware(['auth'])->group(function() {
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name("employees.show");
    Route::get('/employees/detail/leave-office/{id}', [PermissionLeaveOfficeController::class, 'detailLeaveOffice'])->name("employees.detailLeaveOffice");
    Route::get('/employees/detail/leave-menstruation/{id}', [MenstruationLeaveController::class, 'detailLeaveMenstruation'])->name("employees.detailLeaveOffice");
    Route::middleware('role:admin')->group(function() {
        // begin::division
        Route::get('/division/json', [DivisionController::class, 'json'])->name('division.json');
        Route::get('/division/get', [DivisionController::class, 'getData'])->name('division.getData');
        Route::resource('division', DivisionController::class);
        Route::post('/division/{id}', [DivisionController::class, 'update'])->name('division.update');
        // end::division
    
        // begin::department
        Route::get('/department/json', [DepartmentController::class, 'json'])->name('department.json');
        Route::get('/department/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
        Route::put('/department/edit/{id}', [DepartmentController::class, 'update'])->name('department.update');
        Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
        Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
        // end::department
    
        // begin::employee status
        Route::get('/employee-status/json', [EmployeeStatusController::class, 'json'])->name('employee-status.json');
        Route::get('/employee-status/edit/{id}', [EmployeeStatusController::class, 'edit'])->name('employee-status.edit');
        Route::put('/employee-status/{id}', [EmployeeStatusController::class, 'update'])->name('employee-status.update');
        Route::post('/employee-status/store', [EmployeeStatusController::class, 'store'])->name('employee-status.store');
        Route::delete('/employee-status/{id}', [EmployeeStatusController::class, 'destroy'])->name('employee-status.destroy');
        // end::employee status
        
        // begin::position
        Route::get('/position/json', [PositionController::class, 'json'])->name('position.json');
        Route::get('/position/getData/{id}', [PositionController::class, 'getData'])->name('position.getData');
        Route::resource('position', PositionController::class);
        Route::post('/position/{id}', [PositionController::class, 'update'])->name('position.update');
        // end::position
    
        // begin::employee
        Route::get('/employees/detail/getData', [EmployeeController::class, 'getData'])->name("employees.getData");
        Route::get('/employees/getDivision/{id}', [EmployeeController::class, 'getDivision'])->name("employees.getDivision");
        Route::get('/employees/data/json', [EmployeeController::class, 'json'])->name("employees.json");
        Route::post('/employees/{id}', [EmployeeController::class, 'update'])->name("employees.update");
        Route::resource('employees', EmployeeController::class);
        // end::employee
    
        // begin::region
        Route::get('/region/getRegency/{provinceId}', [RegionController::class, 'getRegency'])->name("region.getRegency");
        Route::get('/region/getDistrict/{regencyId}', [RegionController::class, 'getDistrict'])->name("region.getDistrict");
        Route::get('/region/getVillage/{districtId}', [RegionController::class, 'getVillage'])->name("region.getVillage");
        // end::region
    
        // begin::permission
        Route::prefix('permission')->group(function() {
            Route::get('/leave-office/detail/{id}', [PermissionLeaveOfficeController::class, 'detail'])->name("leave-office.detail");
            Route::get('leave-office/json', [PermissionLeaveOfficeController::class, 'json'])->name('permission.leave-office.json');
            Route::resource('leave-office', PermissionLeaveOfficeController::class);
            Route::get('/leave-menstruation/detail/{id}', [MenstruationLeaveController::class, 'detail'])->name("leave-menstruation.detail");
            Route::get('leave-menstruation/json', [MenstruationLeaveController::class, 'json'])->name('permission.leave-menstruation.json');
            Route::resource('leave-menstruation', MenstruationLeaveController::class);
        });
        // end::permission
    
        // begin::organization-structure
        Route::get('/organization-structure/index', [OrganizationStructureController::class, 'index'])->name('organization-structure.index');
        Route::get('/organization-structure/edit/{id}/{type}', [OrganizationStructureController::class, 'edit'])->name('organization-structure.edit');
        Route::get('/organization-structure/add-form/{type}', [OrganizationStructureController::class, 'addForm'])->name('organization-structure.addForm');
        Route::get('/organization-structure/add-division', [OrganizationStructureController::class, 'addDivision'])->name('organization-structure.addDivision');
       // end::organization-structure
    });
});

Route::middleware(['auth', 'role:satpam'])->group(function() {
    Route::get('/leave-office', [PermissionLeaveOfficeController::class, 'showConfirm'])->name('leave-office.confirm.index');
    Route::put('/leave-office/confirm/{id}', [PermissionLeaveOfficeController::class, 'confirm'])->name('leave-office.confirm');
    Route::get('/leave-office/json', [PermissionLeaveOfficeController::class, 'json'])->name('permission.leave-office.confirm.json');
    Route::get('/leave-menstruation', [MenstruationLeaveController::class, 'showConfirm'])->name('leave-menstruation.confirm.index');
    Route::put('/leave-menstruation/confirm/{id}', [MenstruationLeaveController::class, 'confirm'])->name('leave-menstruation.confirm');
    Route::get('/leave-menstruation/json', [MenstruationLeaveController::class, 'json'])->name('permission.leave-menstruation.confirm.json');
});

Route::get('/leave-office/confirm/br/{id}', [PermissionLeaveOfficeController::class, 'confirmByBarcode'])->name('leave-office.confirm.barcode');
Route::post('/leave-office/confirm/br/{id}', [PermissionLeaveOfficeController::class, 'confirmBarcode'])->name('leave-office.confirm.barcode.store');
Route::get('/leave-menstruation/confirm/br/{id}', [MenstruationLeaveController::class, 'confirmByBarcode'])->name('leave-menstruation.confirm.barcode');
Route::post('/leave-menstruation/confirm/br/{id}', [MenstruationLeaveController::class, 'confirmBarcode'])->name('leave-menstruation.confirm.barcode.store');
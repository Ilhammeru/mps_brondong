<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RegionController;
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
    // begin::division
    Route::get('/division/json', [DivisionController::class, 'json'])->name('division.json');
    Route::get('/division/get', [DivisionController::class, 'getData'])->name('division.getData');
    Route::resource('division', DivisionController::class);
    Route::post('/division/{id}', [DivisionController::class, 'update'])->name('division.update');
    // end::division
    
    // begin::position
    Route::get('/position/json', [PositionController::class, 'json'])->name('position.json');
    Route::resource('position', PositionController::class);
    Route::post('/position/{id}', [PositionController::class, 'update'])->name('position.update');
    // end::position

    // begin::employee
    Route::get('/employee/json', [EmployeeController::class, 'json'])->name("employee.json");
    Route::resource('employee', EmployeeController::class);
    // end::employee

    // begin::region
    Route::get('/region/getRegency/{provinceId}', [RegionController::class, 'getRegency'])->name("region.getRegency");
    Route::get('/region/getDistrict/{regencyId}', [RegionController::class, 'getDistrict'])->name("region.getDistrict");
    Route::get('/region/getVillage/{districtId}', [RegionController::class, 'getVillage'])->name("region.getVillage");
    // end::region
});
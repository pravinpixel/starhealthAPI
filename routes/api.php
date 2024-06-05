<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;

Route::get('/', function () { return 'Welcome Star Health'; });

Route::post('emailverfiy', [EmployeeController::class, 'emailverfiy'])->name('emailverfiy');
Route::post('otp-verfiy', [EmployeeController::class, 'otpverfiy'])->name('otpverfiy');


Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('employee/view', [EmployeeController::class, 'getEmployee'])->name('getEmployee');
    Route::post('employee/save', [EmployeeController::class, 'save'])->name('save');
    Route::post('employee/logout', [EmployeeController::class, 'employeelogout'])->name('employeelogout');
});
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EssientialController;


Route::get('/', function () { return 'Welcome Star Health'; });
Route::get('/view', [EmployeeController::class, 'view']);
Route::group([
   'middleware' => 'api',
], function () {
Route::get('createRandomToken', [EmployeeController::class, 'createRandomToken'])->name('createRandomToken');
Route::post('emailverfiy', [EmployeeController::class, 'emailverfiy'])->name('emailverfiy');
Route::post('otp-verfiy', [EmployeeController::class, 'otpverfiy'])->name('otpverfiy');
Route::post('resend-otp', [EmployeeController::class, 'resendOtp'])->name('resendOtp');

});
Route::group([
    'middleware' => 'auth:api',
], function () {
     #..Employee...
     Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
        Route::get('/view', 'getEmployee')->name('getEmployee');
        Route::post('/save', 'save')->name('save');
        Route::post('/update', 'update')->name('update');
        Route::post('/logout', 'employeelogout')->name('employeelogout');
     });
      #..Essiential...
      Route::prefix('essiential')->controller(EssientialController::class)->group(function () {
        Route::get('/', 'getdata')->name('getdata');
        Route::get('/{id}', 'getCity')->name('getCity');
     });
});
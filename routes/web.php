<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChangePasswordController;

#..login..
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('login', 'loginpage')->name('login.page');
    Route::post('loginsave', 'loginsave')->name('loginsave');
});

#..forgot password..
Route::get('forgot-password', [ForgotPasswordController::class, 'ForgotPasswordForm'])->name('forgot.password');
Route::post('save', [ForgotPasswordController::class, 'submitForgotPasswordForm'])->name('forgot.save');


Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.view');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
         #..Employee...
         Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
           Route::get('/register-list', 'registerlist')->name('register');
           Route::get('/submitted-gallery', 'galleryview')->name('registernew');
           Route::get('/shortlist-gallery', 'galleryview')->name('shortlistnew');
           Route::get('/finallist-gallery', 'galleryview')->name('finalnew');
           Route::get('/short-list', 'registerlist')->name('shortlist');
           Route::get('/final-list', 'registerlist')->name('final');
           Route::post('/select', 'statusselect')->name('select');
           Route::get('/view', 'view')->name('view');
        });
         #..user
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::get('create', 'create')->name('user.create');
        Route::post('save', 'save')->name('user.save');
        Route::get('edit/{id}', 'get')->name('user.get');
        Route::post('/update', 'update')->name('user.update');
        Route::delete('/{id}', 'delete')->name('user.delete');
    });
    Route::prefix('change-password')->controller(ChangePasswordController::class)->group(function () {
        Route::get('/', 'index')->name('change.password.get');
        Route::post('updatepassword', 'updatepassword')->name('change-password');
    });
});
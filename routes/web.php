<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgotPasswordController;

#..login..
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('login', 'index')->name('index.login');
    Route::post('login', 'login')->name('login');
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
           Route::get('/short-list', 'registerlist')->name('shortlist');
           Route::get('/final-list', 'registerlist')->name('final');
           Route::post('/select', 'statusselect')->name('select');
        });
});
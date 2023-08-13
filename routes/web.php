<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => AuthController::class], function(){
    Route::get('/', 'index')->name('auth');
    Route::get('/auth/google', 'googleAuth')->name('googleAuth');
    Route::get('/auth/yandex', 'yandexAuth')->name('yandexAuth');
    Route::get('/callback/google', 'googleCallback')->name('googleCallback');
    Route::get('/callback/yandex', 'yandexCallback')->name('yandexCallback');
});

Route::middleware('auth')->group(function(){
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
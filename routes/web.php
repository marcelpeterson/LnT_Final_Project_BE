<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsLogin;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([IsLogin::class])->group(function () {
    Route::get('/', [ItemController::class, 'getItemPage'])->name('home');
    
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/create-item', [ItemController::class, 'getCreatePage']);
        Route::post('/create-item-post', [ItemController::class, 'createItem']);
        Route::get('/delete-item/{id}', [ItemController::class, 'deleteItem']);
        Route::get('/edit-item/{id}', [ItemController::class, 'getEditPage']);
        Route::post('/edit-item-post/{id}', [ItemController::class, 'editItem']);
    });
});



Route::get('/login', [AuthController::class, 'getLoginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'getRegisterPage'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\SuperAdminLoginController;


Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminLoginController::class ,'showLoginForm'])->name('super-admin.login');
    Route::post('/login', [SuperAdminLoginController::class,'login']);
    Route::post('/logout', [SuperAdminLoginController::class,'logout'])->name('super-admin.logout');
});

Route::middleware(['auth:super-admin'])->prefix('super-admin')->group(function () {
    Route::get('/dashboard', [TenantController::class,'dashboard'])->name('super-admin.dashboard');
    Route::get('/tenants/create', [TenantController::class,'create'])->name('super-admin.tenants.create');
    Route::post('/tenants/store', [TenantController::class,'store'])->name('super-admin.tenants.store');
});




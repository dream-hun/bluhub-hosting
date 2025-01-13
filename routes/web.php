<?php

use App\Http\Controllers\Admin\DomainPricingController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', IndexController::class)->name('home');
Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
Route::post('/domains/search', [DomainController::class, 'search'])->name('domains.search');
Route::get('/domains/results', [DomainController::class, 'results'])->name('domains.results');

Auth::routes();

// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin'], 'as' => 'admin.'], function () {
    Route::resource('pricing', DomainPricingController::class);
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

<?php

use App\Http\Controllers\Admin\DomainPricingController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Public routes
Route::get('/', IndexController::class)->name('home');
Route::get('search', [\App\Http\Controllers\SearchDomainController::class, 'index'])->name('domains.index');
Route::post('search', [\App\Http\Controllers\SearchDomainController::class, 'search'])->name('domains.search');

// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin'], 'as' => 'admin.'], function () {
    Route::resource('domain-pricings', DomainPricingController::class);

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::resource('domains', \App\Http\Controllers\Admin\DomainController::class);

    Route::resource('users', UsersController::class);

    Route::resource('permissions', PermissionsController::class);

    Route::resource('roles', RolesController::class);

    Route::post('settings/media', [SettingController::class, 'storeMedia'])->name('settings.storeMedia');
    Route::post('settings/ckmedia', [SettingController::class, 'storeCKEditorImages'])->name('settings.storeCKEditorImages');
    Route::resource('settings', SettingController::class);
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
        Route::post('profile/two-factor', [ChangePasswordController::class, 'toggleTwoFactor'])->name('password.toggleTwoFactor');
    }
});

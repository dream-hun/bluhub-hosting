<?php

use App\Http\Controllers\DomainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

Route::prefix('domains')->group(function () {
    Route::post('search', [DomainController::class, 'search']);
    Route::post('register', [DomainController::class, 'register']);
    Route::get('info', [DomainController::class, 'info']);
});

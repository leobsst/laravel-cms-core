<?php

use Illuminate\Support\Facades\Route;
use Leobsst\LaravelCmsCore\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name(value: 'api.core.')->group(callback: function (): void {
    // Routes with User Authentication
    Route::middleware(['auth:api'])->group(callback: function (): void {
        Route::get(uri: '/users', action: [UserController::class, 'index'])->name(name: 'users.index');
        Route::get(uri: '/user', action: [UserController::class, 'show'])->name(name: 'users.show');
    });

    // Routes with Client Credentials
    Route::middleware(['client_credentials'])->group(callback: function (): void {
        //
    });
});

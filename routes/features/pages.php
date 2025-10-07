<?php

use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Feature;
use Leobsst\LaravelCmsCore\Http\Middleware\Maintenance;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

if (\Illuminate\Support\Facades\Schema::hasTable('features')) {
    Feature::when('pages', function () {
        Route::middleware('web')->name('core.pages.')->group(function () {
            Route::middleware([Maintenance::class])->group(function () {
                /* Get page */
                Route::fallback(\Leobsst\LaravelCmsCore\Livewire\Page\Show::class)->name('show');
            });
        });
    });
}

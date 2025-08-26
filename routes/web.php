<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Leobsst\LaravelCmsCore\Http\Controllers\HomeController;
use Leobsst\LaravelCmsCore\Http\Controllers\Logout;
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

Route::name('core.')->group(function () {
    // Session
    Route::get('/login', fn () => redirect(Filament::getPanel('dashboard')->getLoginUrl()))->name('login');
    Route::get('/logout', [Logout::class, 'logout'])->name('logout');

    // Sitemap
    Route::get('/sitemap.xml', [HomeController::class, 'getSiteMap'])->name('sitemap');

    // Filemanager
    // Route::group(['prefix' => 'dashboard/files/filemanager', 'middleware' => ['web', 'auth', Owner::class]], function () {
    //    Lfm::routes();
    // });

    Route::group(['middleware' => Maintenance::class], function () {
        /* Get page */
        Route::get('/{slug?}', Leobsst\LaravelCmsCore\Livewire\Page\Show::class)->name('page.show')->middleware(EnsureFeaturesAreActive::using('pages'));
    });
});

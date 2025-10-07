<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Leobsst\LaravelCmsCore\Http\Controllers\FileExplorer\OpenFileController;
use Leobsst\LaravelCmsCore\Http\Controllers\HomeController;
use Leobsst\LaravelCmsCore\Http\Controllers\Logout;

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

    // File Explorer
    Route::get('/files/open', OpenFileController::class)
        ->name('file-explorer.open');

    // Sitemap
    Route::get('/sitemap.xml', [HomeController::class, 'getSiteMap'])->name('sitemap');
});

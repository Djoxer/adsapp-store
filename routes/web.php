<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Merchant + Admin
    Route::middleware('role:merchant,agency,admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/ads',           [AdController::class, 'index'])->name('ads.index');
        Route::get('/ads/create',    [AdController::class, 'create'])->name('ads.create');
        Route::post('/ads',          [AdController::class, 'store'])->name('ads.store');
        Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
        Route::patch('/ads/{ad}',    [AdController::class, 'update'])->name('ads.update');
        Route::delete('/ads/{ad}',   [AdController::class, 'destroy'])->name('ads.destroy');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/slots', [SlotController::class, 'index'])->name('slots.index');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
    });

    // Buyer
    Route::middleware('role:buyer,merchant,agency,admin')->group(function () {
        Route::get('/catalog',           [CatalogController::class, 'index'])->name('catalog');
        Route::get('/catalog/ranking',   [CatalogController::class, 'ranking'])->name('catalog.ranking');
        Route::get('/catalog/hotspots',  [CatalogController::class, 'hotspots'])->name('catalog.hotspots');
        Route::get('/catalog/analytics', [CatalogController::class, 'analytics'])->name('catalog.analytics');
    });

    // Alle Rollen
    Route::post('/bookmarks/{ad}',  [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks',        [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

require __DIR__.'/auth.php';

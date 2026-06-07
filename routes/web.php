<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AdEventController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SlotBookingController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ──────────────────────────────────────────────────────────────────

Route::get('/', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/catalog/ranking', [CatalogController::class, 'ranking'])->name('catalog.ranking');
Route::get('/hotspots', [HotspotController::class, 'index'])->name('catalog.hotspots');
Route::get('/hotspots/{slug}', [HotspotController::class, 'show'])->name('catalog.hotspot.show');
Route::post('/events/track', [AdEventController::class, 'track'])->name('events.track');

// Nur numerische IDs — verhindert dass /ads/create hier gematcht wird
Route::get('/ads/{ad}', [AdController::class, 'show'])->name('ads.show')->where('ad', '[0-9]+');
Route::get('/ads/{ad}/click', [AdController::class, 'click'])->name('ads.click')->where('ad', '[0-9]+');

// ── AUTH REQUIRED ────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    Route::get('/catalog/analytics', [CatalogController::class, 'analytics'])->name('catalog.analytics');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/slots', [\App\Http\Controllers\Admin\SlotApprovalController::class, 'index'])->name('slots');
        Route::post('/slots/{booking}/approve', [\App\Http\Controllers\Admin\SlotApprovalController::class, 'approve'])->name('slots.approve');
        Route::post('/slots/{booking}/reject', [\App\Http\Controllers\Admin\SlotApprovalController::class, 'reject'])->name('slots.reject');
        Route::get('/merchants', [\App\Http\Controllers\Admin\MerchantApprovalController::class, 'index'])->name('merchants');
        Route::post('/merchants/{merchant}/approve', [\App\Http\Controllers\Admin\MerchantApprovalController::class, 'approve'])->name('merchants.approve');
        Route::post('/merchants/{merchant}/reject', [\App\Http\Controllers\Admin\MerchantApprovalController::class, 'reject'])->name('merchants.reject');
        Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users');
        Route::patch('/users/{user}/role', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/ban', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleBan'])->name('users.ban');
        Route::patch('/users/{user}/merchant', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleMerchant'])->name('users.merchant');
    });

    // Merchant + Agency + Admin
    Route::middleware('role:merchant,agency,admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
        Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
        Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
        Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
        Route::patch('/ads/{ad}/toggle-status', [AdController::class, 'toggleStatus'])->name('ads.toggle-status');
        Route::patch('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
        Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/slots', [SlotBookingController::class, 'index'])->name('slots.index');
        Route::post('/slots/book', [SlotBookingController::class, 'store'])->name('slots.book');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    });

    // Alle eingeloggten Rollen
    Route::post('/bookmarks/{ad}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
    Route::post('/settings/notifications/seen', [SettingsController::class, 'markNotificationsSeen'])->name('settings.notifications.seen');
    Route::get('/help', [\App\Http\Controllers\HelpController::class, 'index'])->name('help');
    Route::get('/privacy', [\App\Http\Controllers\HelpController::class, 'privacy'])->name('privacy');
});

require __DIR__.'/auth.php';

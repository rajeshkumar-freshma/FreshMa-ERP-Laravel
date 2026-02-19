<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\Documentation\LayoutBuilderController;
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Supplier\HomeController;
use App\Http\Controllers\Supplier\OrderRequestController;
use App\Http\Controllers\Supplier\SalesPurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('homeindex');

Route::get('/privacy-policy', [DashboardController::class, 'privacyPolicies'])->name('privacyPolicies');

Route::prefix(env('SUPPLIER_PREFIX'))->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});


Route::prefix(env('SUPPLIER_PREFIX'))->middleware(['auth'])->group(function () {

    Route::get('/', [HomeController::class, 'home'])->name('dashboard');

    Route::resource('/order-request', OrderRequestController::class);

    Route::resource('sales-order', SalesPurchaseController::class);

    Route::get('logout', [LoginController::class, 'logout'])
            ->middleware('admin')
            ->name('logout');
});


Route::get('s3-logo-upload', [SettingsController::class, 's3LogoUpload'])->name('settings.s3LogoUpload');

// Documentations pages
Route::prefix('documentation')->group(function () {
    Route::get('getting-started/references', [ReferencesController::class, 'index']);
    Route::get('getting-started/changelog', [PagesController::class, 'index']);
    Route::resource('layout-builder', LayoutBuilderController::class)->only(['store']);
});

Route::middleware('auth')->group(function () {
    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
        Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
        Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });
});

Route::resource('users', UsersController::class);

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

// require __DIR__.'/admin.php';

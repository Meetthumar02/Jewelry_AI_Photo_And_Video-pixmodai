<?php

use Illuminate\Support\Facades\Route;

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
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
    Route::get('/verify-email/{token}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->name('verify.email');
});

Route::middleware('auth')->group(function () {
    Route::get('/catalog-studio', [App\Http\Controllers\User\CatalogStudioController::class, 'index'])->name('catalog.studio');
    Route::get('/dashboard', [App\Http\Controllers\User\UserController::class, 'dashboard'])->name('user.dashboard');

    Route::post('/generate.prompt', [App\Http\Controllers\User\CatalogStudioController::class, 'generatePrompt'])->name('generate.prompt');
    Route::get('/get-project-details/{id}', [App\Http\Controllers\User\CatalogStudioController::class, 'getProjectDetails'])->name('get.project.details');

    Route::get('/catalog-library', [App\Http\Controllers\User\UserController::class, 'catalogLibrary'])->name('catalog.library');
    Route::get('/generation-history', [App\Http\Controllers\User\UserController::class, 'generationHistory'])->name('generation.history');
    Route::get('/favorites', [App\Http\Controllers\User\UserController::class, 'favorites'])->name('favorites');
    Route::post('/toggle-favorite', [App\Http\Controllers\User\UserController::class, 'toggleFavorite'])->name('toggle.favorite');
    Route::get('/contact', [App\Http\Controllers\User\UserController::class, 'contact'])->name('contact');

    Route::prefix('settings')
        ->name('settings.')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\Setting\AccountController::class, 'index'])->name('account');
            Route::get('/account', [App\Http\Controllers\Setting\AccountController::class, 'index']);
            Route::get('/ai', [App\Http\Controllers\Setting\AiSettingController::class, 'index'])->name('ai');
            Route::post('/ai/logo', [App\Http\Controllers\Setting\AiSettingController::class, 'updateLogo'])->name('ai.logo');
            Route::get('/notifications', [App\Http\Controllers\Setting\NotificationController::class, 'index'])->name('notifications');
            Route::get('/security', [App\Http\Controllers\Setting\SecurityController::class, 'index'])->name('security');
            Route::get('/subscriptions', [App\Http\Controllers\Setting\SubscriptionController::class, 'index'])->name('subscriptions');
            Route::get('/billing', [App\Http\Controllers\Setting\BillingController::class, 'index'])->name('billing');
        });

    Route::get('/contact', [App\Http\Controllers\User\ContactController::class, 'index'])->name('contact');
    Route::post('/contact-store', [App\Http\Controllers\User\ContactController::class, 'store'])->name('contact.store');

    Route::post('/topup/create-order', [App\Http\Controllers\User\TopupController::class, 'createOrder'])->name('cashfree.create');

    Route::get('/cashfree/success', [App\Http\Controllers\User\TopupController::class, 'success'])->name('cashfree.success');
    Route::post('/cashfree/webhook', [App\Http\Controllers\User\TopupController::class, 'webhook'])->name('cashfree.webhook');

    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::prefix('ai-photoshoot')
        ->name('ai.photoshoot.')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\User\AIPhotoShootController::class, 'index'])->name('index');
            Route::post('/upload', [App\Http\Controllers\User\AIPhotoShootController::class, 'uploadImage'])->name('upload');
            Route::post('/start', [App\Http\Controllers\User\AIPhotoShootController::class, 'startShoot'])->name('start');
            Route::get('/status/{id}', [App\Http\Controllers\User\AIPhotoShootController::class, 'getStatus'])->name('status');
            Route::get('/download/{id}', [App\Http\Controllers\User\AIPhotoShootController::class, 'downloadImage'])->name('download');
            Route::delete('/{id}', [App\Http\Controllers\User\AIPhotoShootController::class, 'destroy'])->name('destroy');
        });

    // Creative AI Routes
    Route::prefix('creative-ai')
        ->name('creative.ai.')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\User\CreativeAIController::class, 'index'])->name('index');
            Route::post('/upload', [App\Http\Controllers\User\CreativeAIController::class, 'uploadImage'])->name('upload');
            Route::post('/generate', [App\Http\Controllers\User\CreativeAIController::class, 'generate'])->name('generate');
            Route::get('/status/{id}', [App\Http\Controllers\User\CreativeAIController::class, 'getStatus'])->name('status');
            Route::get('/download/{id}', [App\Http\Controllers\User\CreativeAIController::class, 'downloadImage'])->name('download');
            Route::delete('/{id}', [App\Http\Controllers\User\CreativeAIController::class, 'destroy'])->name('destroy');
            Route::get('/history', [App\Http\Controllers\User\CreativeAIController::class, 'history'])->name('history');
            Route::get('/get-categories', [App\Http\Controllers\User\CreativeAIController::class, 'getCategories'])->name('get.categories');
            Route::get('/get-products', [App\Http\Controllers\User\CreativeAIController::class, 'getProducts'])->name('get.products');
            Route::get('/get-model-designs', [App\Http\Controllers\User\CreativeAIController::class, 'getModelDesigns'])->name('get.model.designs');
        });

    Route::get('/ai-studio', [App\Http\Controllers\User\CreativeAIController::class, 'index']);


});

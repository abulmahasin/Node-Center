<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $apps = auth()->user()->apps()->with(['metrics' => function($q) {
        $q->latest()->take(10);
    }])->get();

    $healthScoringService = app(\App\Services\HealthScoringService::class);

    return view('dashboard', compact('apps', 'healthScoringService'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/documentation', function () {
    return view('documentation');
})->middleware(['auth', 'verified'])->name('documentation');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apps', [\App\Http\Controllers\AppController::class, 'index'])->name('apps.index');
    Route::get('/apps/create', [\App\Http\Controllers\AppController::class, 'create'])->name('apps.create');
    Route::post('/apps', [\App\Http\Controllers\AppController::class, 'store'])->name('apps.store');
    Route::get('/apps/{app}', [\App\Http\Controllers\AppController::class, 'show'])->name('apps.show');
    Route::get('/apps/{app}/edit', [\App\Http\Controllers\AppController::class, 'edit'])->name('apps.edit');
    Route::put('/apps/{app}', [\App\Http\Controllers\AppController::class, 'update'])->name('apps.update');
    Route::delete('/apps/{app}', [\App\Http\Controllers\AppController::class, 'destroy'])->name('apps.destroy');
    Route::post('/apps/{app}/token', [\App\Http\Controllers\AppController::class, 'generateToken'])->name('apps.token');
    Route::post('/apps/{app}/ping', [\App\Http\Controllers\AppController::class, 'ping'])->name('apps.ping');

    // Settings & Alert Logs
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-telegram', [\App\Http\Controllers\SettingController::class, 'testTelegram'])->name('settings.test-telegram');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

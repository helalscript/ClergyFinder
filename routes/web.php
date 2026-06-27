<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('home');

require __DIR__ . '/auth.php';

// Development & Integration Testbed Routes
Route::prefix('dev')->group(function () {
    Route::get('/live-session-test', [\App\Http\Controllers\Dev\LiveSessionTestController::class, 'index'])->name('dev.live_session_test');
    Route::get('/live-session-test/users', [\App\Http\Controllers\Dev\LiveSessionTestController::class, 'getTestUsers'])->name('dev.live_session_test.users');
    Route::post('/live-session-test/quick-token', [\App\Http\Controllers\Dev\LiveSessionTestController::class, 'getQuickToken'])->name('dev.live_session_test.quick_token');
    Route::post('/live-session-test/create-user', [\App\Http\Controllers\Dev\LiveSessionTestController::class, 'createTestUser'])->name('dev.live_session_test.create_user');
});


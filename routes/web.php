<?php

declare(strict_types=1);

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\PackageIndex;
use App\Livewire\Admin\PassageIndex;
use App\Livewire\Admin\QuestionImporter;
use App\Livewire\Admin\QuestionIndex;
use App\Livewire\Admin\TestBuilder;
use App\Livewire\Admin\TestIndex;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Cbt\ExamRunner;
use App\Livewire\Portal\Checkout;
use App\Livewire\Portal\Dashboard as PortalDashboard;
use App\Livewire\Portal\Onboarding;
use App\Livewire\Portal\Profile;
use App\Livewire\Portal\TestInstructions;
use App\Livewire\Portal\TestResult;
use App\Livewire\Portal\TestSeries;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Google OAuth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('portal.series');
})->name('logout');

// Impersonation: one-time, short-lived magic login link
Route::get('/impersonate/{token}', ImpersonationController::class)->name('impersonate');

// Student Onboarding
Route::get('/onboarding', Onboarding::class)->middleware('auth')->name('portal.onboarding');

// Student Profile
Route::get('/profile', Profile::class)->middleware('auth')->name('portal.profile');

// Student Portal
Route::get('/', PortalDashboard::class)->middleware('auth')->name('portal.dashboard');
Route::get('/test-series', TestSeries::class)->name('portal.series');
Route::get('/checkout/{package:slug}', Checkout::class)->middleware('auth')->name('portal.checkout');
Route::get('/test/{test:slug}/instructions', TestInstructions::class)->middleware('auth')->name('portal.test.instructions');
Route::get('/cbt/attempt/{attempt}', ExamRunner::class)->middleware('auth')->name('cbt.runner');
Route::get('/attempt/{attempt}/result', TestResult::class)->middleware('auth')->name('portal.test.result');

// Admin Workspace (Guarded by AdminMiddleware)
Route::prefix('admin')->name('admin.')->middleware([AdminMiddleware::class])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/users', UserIndex::class)->name('users');
    Route::get('/questions', QuestionIndex::class)->name('questions');
    Route::get('/import', QuestionImporter::class)->name('import');
    Route::get('/passages', PassageIndex::class)->name('passages');
    Route::get('/tests', TestIndex::class)->name('tests');
    Route::get('/test-builder', TestBuilder::class)->name('test-builder');
    Route::get('/packages', PackageIndex::class)->name('packages');
});

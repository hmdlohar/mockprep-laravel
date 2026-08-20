<?php

declare(strict_types=1);

use App\Http\Controllers\GoogleAuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\PackageIndex;
use App\Livewire\Admin\PassageIndex;
use App\Livewire\Admin\QuestionImporter;
use App\Livewire\Admin\QuestionIndex;
use App\Livewire\Admin\TestBuilder;
use App\Livewire\Admin\TestIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Cbt\ExamRunner;
use App\Livewire\Portal\Onboarding;
use App\Livewire\Portal\TestCatalog;
use App\Livewire\Portal\TestInstructions;
use App\Livewire\Portal\TestResult;
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
    return redirect()->route('portal.catalog');
})->name('logout');

// Student Onboarding
Route::get('/onboarding', Onboarding::class)->middleware('auth')->name('portal.onboarding');

// Student Portal & Catalog
Route::get('/', TestCatalog::class)->name('portal.catalog');
Route::get('/test/{test:slug}/instructions', TestInstructions::class)->name('portal.test.instructions');
Route::get('/cbt/attempt/{attempt}', ExamRunner::class)->name('cbt.runner');
Route::get('/attempt/{attempt}/result', TestResult::class)->name('portal.test.result');

// Admin Workspace (Guarded by AdminMiddleware)
Route::prefix('admin')->name('admin.')->middleware([AdminMiddleware::class])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/questions', QuestionIndex::class)->name('questions');
    Route::get('/import', QuestionImporter::class)->name('import');
    Route::get('/passages', PassageIndex::class)->name('passages');
    Route::get('/tests', TestIndex::class)->name('tests');
    Route::get('/test-builder', TestBuilder::class)->name('test-builder');
    Route::get('/packages', PackageIndex::class)->name('packages');
});

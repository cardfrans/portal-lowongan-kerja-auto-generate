<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PosterController;
use App\Http\Controllers\Company\JobVacancyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Intelligent Dashboard Redirection based on role
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('company.vacancies.index');
})->middleware(['auth'])->name('dashboard');

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Company Portal Routes
Route::middleware(['auth', 'role:company', 'throttle:60,1'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('company.vacancies.index'))->name('dashboard');
    Route::resource('vacancies', JobVacancyController::class);
});

// Admin Portal Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/vacancies/{jobVacancy}', [AdminDashboardController::class, 'show'])->name('vacancies.show');
    Route::post('/vacancies/{jobVacancy}/approve', [AdminDashboardController::class, 'approve'])->name('vacancies.approve');
    Route::post('/vacancies/{jobVacancy}/reject', [AdminDashboardController::class, 'reject'])->name('vacancies.reject');

    Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs.index');
    Route::patch('/logs/{log}/read', [AdminDashboardController::class, 'markLogAsRead'])->name('logs.read');

    // Poster Generation
    Route::get('/posters/{jobVacancy}/preview', [PosterController::class, 'preview'])->name('posters.preview');
    Route::get('/posters/{jobVacancy}/render/{templateId}', [PosterController::class, 'renderHtml'])->name('posters.render');
    Route::post('/posters/{jobVacancy}/generate', [PosterController::class, 'generate'])->name('posters.generate');
    Route::get('/posters/{jobVacancy}/download', [PosterController::class, 'download'])->name('posters.download');
});

require __DIR__.'/auth.php';

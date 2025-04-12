<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\InvestmentsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\InvestorsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/projects', [ProjectsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('projects');

Route::get('/projects/{id}', [ProjectsController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('projects.show');

Route::get('/investments', [InvestmentsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('investments');

Route::get('/my-profile', [InvestorsController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('my-profile');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/calculate', [CalculatorController::class, 'calculate']);

Route::get('/average-retail-cost', [CalculatorController::class, 'averageRetailCost']);

require __DIR__.'/auth.php';

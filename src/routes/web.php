<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\InvestmentsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\InvestorsController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

Route::get('/my-portfolio', [InvestorsController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('my-portfolio');

Route::get('/investments/{investment}/tokens', [InvestmentsController::class, 'getTokens'])
    ->middleware(['auth', 'verified'])
    ->name('get-tokens');

Route::post('/tokens/transfer', [InvestmentsController::class, 'transferTokens'])
    ->middleware(['auth', 'verified'])
    ->name('transfer-tokens');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::view('/about', 'aboutus.index')->name('about');

Route::post('/calculate', [CalculatorController::class, 'calculate']);

Route::get('/average-retail-cost', [CalculatorController::class, 'averageRetailCost']);

Route::post('/invest', [InvestmentsController::class, 'store'])->name('investments.store');

require __DIR__.'/auth.php';

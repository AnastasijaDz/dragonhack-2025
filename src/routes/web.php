<?php
    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmersController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/farmers', [FarmersController::class, 'index']);

Route::get('/farmers/create', [FarmersController::class, 'create']);

Route::post('/farmers/store', [FarmersController::class, 'post']);


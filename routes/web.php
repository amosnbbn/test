<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

// login
Route::get('/login', [AuthController::class,'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

// semua halaman harus login
Route::middleware('auth.session')->group(function() {
    Route::get('/', [MovieController::class,'index'])->name('movies.index');
    Route::get('/movie/{imdbID}', [MovieController::class,'show'])->name('movies.show');
    Route::post('/favorite/{imdbID}', [MovieController::class,'toggleFavorite'])->name('movies.favorite');
    Route::get('/favorites', [MovieController::class,'favorites'])->name('movies.favorites');
    Route::get('/lang/{lang}', [MovieController::class,'changeLanguage'])->name('movies.lang');
});
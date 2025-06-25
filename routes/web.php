<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowController;

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

// Rotta per la ricerca film
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');

// Rotta per i dettagli di un singolo film (opzionale, ma consigliato)
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/shows', [ShowController::class, 'index'])->name('series.index');

// Rotta per la ricerca serie TV
Route::get('/shows/search', [ShowController::class, 'search'])->name('series.search');

// Rotta per i dettagli di un singolo film (opzionale, ma consigliato)
Route::get('/shows/{id}', [ShowController::class, 'show'])->name('series.show');
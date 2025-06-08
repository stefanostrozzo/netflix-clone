<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/', [MovieController::class, 'index'])->name('movies.index');

// Rotta per i dettagli di un singolo film (opzionale, ma consigliato)
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Potresti aggiungere altre rotte per categorie (es. top rated, per genere)
Route::get('/movies/top-rated', [MovieController::class, 'topRated'])->name('movies.top_rated');
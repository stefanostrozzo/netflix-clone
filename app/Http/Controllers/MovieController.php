<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    protected $tmdbService;

    // Inietta il servizio tramite Dependency Injection
    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Mostra una lista di film popolari.
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);

        try {
            $genres = $this->tmdbService->getMovieGenres();
            $moviesByGenre = [];

            if($genres) {
                foreach($genres as $genre) {
                    $moviesData = $this->tmdbService->getMovieFromGenre($genre['id']);
                    if ($moviesData && isset($moviesData['results'])) {
                        $moviesByGenre[$genre['name']] = $moviesData['results'];
                    }
                }
            }

            $totalPages = 1;

            return view('movies.index', compact('moviesByGenre', 'page', 'totalPages', 'genres'));

        } catch (\Exception $e) {
            Log::error("Errore nel recupero film: " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossibile recuperare i film al momento. Riprova più tardi.');
        }
    }

    /**
     * Mostra i dettagli di un singolo film.
     * Questo richiede un endpoint nel TmdbService per recuperare i dettagli del film.
     */
    public function show($id)
    {
        try {
            // Dovrai aggiungere un metodo getMovieDetails($id) nel tuo TmdbService
            $movie = $this->tmdbService->getMovieDetails($id);

            if (!$movie) {
                abort(404, 'Film non trovato.');
            }

            return view('movies.show', compact('movie'));

        } catch (\Exception $e) {
            Log::error("Errore nel recupero dettagli film (ID: {$id}): " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossibile recuperare i dettagli del film.');
        }
    }
}
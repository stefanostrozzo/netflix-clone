<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Log;

class ShowController extends Controller
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
            // Usiamo solo i generi più popolari per migliorare le performance
            $genres = $this->tmdbService->getPopularShowGenres();
            $showsByGenre = [];

            if($genres) {
                foreach($genres as $genre) {
                    $showsData = $this->tmdbService->getShowFromGenre($genre['id']);
                    if ($showsData && isset($showsData['results'])) {
                        $showsByGenre[$genre['name']] = $showsData['results'];
                    }
                }
            }

            $totalPages = 1;

            return view('series.index', compact('showsByGenre', 'page', 'totalPages', 'genres'));

        } catch (\Exception $e) {
            Log::error("Errore nel recupero della serie: " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossibile recuperare le serie al momento. Riprova più tardi.');
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
                abort(404, 'Serie non trovato.');
            }

            return view('movies.show', compact('movie'));

        } catch (\Exception $e) {
            Log::error("Errore nel recupero dettagli della serie (ID: {$id}): " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossibile recuperare i dettagli della serie.');
        }
    }

    /**
     * Cerca serie TV per nome.
     */
    public function search(Request $request)
    {
        $query = $request->query('q', '');
        $page = $request->query('page', 1);

        if (empty($query)) {
            return redirect()->route('series.index');
        }

        try {
            $searchResults = $this->tmdbService->searchShows($query, $page);
            
            if (!$searchResults) {
                return view('series.search', [
                    'shows' => [],
                    'query' => $query,
                    'page' => $page,
                    'totalPages' => 0,
                    'totalResults' => 0
                ]);
            }

            $shows = $searchResults['results'] ?? [];
            $totalPages = min($searchResults['total_pages'] ?? 0, 500); // TMDb limita a 500 pagine
            $totalResults = $searchResults['total_results'] ?? 0;

            return view('series.search', compact('shows', 'query', 'page', 'totalPages', 'totalResults'));

        } catch (\Exception $e) {
            Log::error("Errore nella ricerca serie TV: " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossibile eseguire la ricerca al momento. Riprova più tardi.');
        }
    }
}
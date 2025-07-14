<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        try {
            $movieGenres = $this->tmdbService->getPopularMovieGenres();
            $showGenres = $this->tmdbService->getPopularShowGenres();
            $categories = [];
            // Per ogni genere popolare, raccogli film e serie TV e mischiali
            foreach ($movieGenres as $genre) {
                $items = [];
                $moviesData = $this->tmdbService->getMovieFromGenre($genre['id']);
                if ($moviesData && isset($moviesData['results'])) {
                    $items = array_merge($items, array_slice($moviesData['results'], 0, 20));
                }
                // Cerca anche serie TV con lo stesso nome di genere (se esiste)
                $matchingShowGenre = null;
                foreach ($showGenres as $showGenre) {
                    if ($showGenre['name'] === $genre['name']) {
                        $matchingShowGenre = $showGenre;
                        break;
                    }
                }
                if ($matchingShowGenre) {
                    $showsData = $this->tmdbService->getShowFromGenre($matchingShowGenre['id']);
                    if ($showsData && isset($showsData['results'])) {
                        $items = array_merge($items, array_slice($showsData['results'], 0, 20));
                    }
                }
                shuffle($items);
                $categories[$genre['name']] = $items;
            }
            return view('home', [ 'categories' => $categories ]);
        } catch (\Exception $e) {
            Log::error('Errore nella home: ' . $e->getMessage());
            return view('home', [ 'categories' => [] ]);
        }
    }
} 
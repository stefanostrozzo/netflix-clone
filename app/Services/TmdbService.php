<?php

namespace App\Services; // Assicurati che il namespace sia corretto

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class TmdbService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    public function getPopularMovies(int $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'page' => $page,
        ]);

        return $response->json();
    }

    public function getTopRatedMovies(int $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/movie/top_rated", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'page' => $page,
        ]);

        return $response->json();
    }

    public function discoverMovies(array $params = [], int $page = 1)
    {
        $defaultParams = [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'sort_by' => 'popularity.desc',
            'include_adult' => false,
            'include_video' => false,
            'page' => $page,
        ];

        $response = Http::get("{$this->baseUrl}/discover/movie", array_merge($defaultParams, $params));

        return $response->json();
    }

    public function getMovieGenres()
    {
        $response = Http::get("{$this->baseUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        return $response->json();
    }

    public function getMovieDetails(int $id)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        // Controlla se la richiesta è andata a buon fine e la risposta contiene dati
        if ($response->successful() && $response->json()) {
            return $response->json();
        }

        return null; // O lancia un'eccezione
    }
}
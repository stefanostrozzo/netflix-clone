<?php

namespace App\Services; // Assicurati che il namespace sia corretto

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class TmdbService
{
    protected $apiKey;
    protected $baseUrl;
    protected $rateLimitKey = 'tmdb_api_calls';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    /**
     * Rate limiting per evitare di superare i limiti dell'API
     */
    protected function checkRateLimit()
    {
        $maxCalls = 40; // TMDb permette 40 chiamate per 10 secondi
        $decayMinutes = 1;
        
        if (RateLimiter::tooManyAttempts($this->rateLimitKey, $maxCalls)) {
            $seconds = RateLimiter::availableIn($this->rateLimitKey);
            throw new \Exception("Rate limit exceeded. Try again in {$seconds} seconds.");
        }
        
        RateLimiter::hit($this->rateLimitKey, $decayMinutes * 60);
    }

    /**
     * Metodo ottimizzato per chiamate HTTP con retry e timeout
     */
    protected function makeApiCall($endpoint, $params = [])
    {
        $this->checkRateLimit();
        
        $cacheKey = 'tmdb_api_' . md5($endpoint . serialize($params));
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::timeout(10)
            ->retry(3, 1000)
            ->get("{$this->baseUrl}{$endpoint}", array_merge([
                'api_key' => $this->apiKey,
                'language' => 'it-IT',
            ], $params));

        if ($response->successful()) {
            $data = $response->json();
            // Cache più breve per le chiamate API
            Cache::put($cacheKey, $data, 1800); // 30 minuti
            return $data;
        }

        Log::error("TMDb API error", [
            'endpoint' => $endpoint,
            'status' => $response->status(),
            'response' => $response->body()
        ]);

        return null;
    }

    public function getMovieGenres()
    {
        $response = Http::get("{$this->baseUrl}/genre/movie/list?language=it", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        if($response->successful() && isset($response['genres'])) {
            return $response['genres'];
        }

        return [];
    }

    public function getPopularMovieGenres()
    {
        // Cache per 1 ora
        $cacheKey = 'popular_movie_genres';
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/genre/movie/list?language=it", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        if($response->successful() && isset($response['genres'])) {
            // Prendiamo solo i primi 6 generi più popolari
            $popularGenres = array_slice($response['genres'], 0, 6);
            cache()->put($cacheKey, $popularGenres, 3600); // Cache per 1 ora
            return $popularGenres;
        }

        return [];
    }

    public function getShowGenres()
    {
        $response = Http::get("{$this->baseUrl}/genre/tv/list?language=it", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        if($response->successful() && isset($response['genres'])) {
            return $response['genres'];
        }

        return [];
    }

    public function getPopularShowGenres()
    {
        // Cache per 1 ora
        $cacheKey = 'popular_show_genres';
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/genre/tv/list?language=it", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        if($response->successful() && isset($response['genres'])) {
            // Prendiamo solo i primi 6 generi più popolari
            $popularGenres = array_slice($response['genres'], 0, 6);
            cache()->put($cacheKey, $popularGenres, 3600); // Cache per 1 ora
            return $popularGenres;
        }

        return [];
    }

    public function getMovieFromGenre(string $genreId)
    {
        // Cache per 30 minuti
        $cacheKey = "movies_genre_{$genreId}";
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/discover/movie", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'with_genres' => $genreId,
            'sort_by' => 'popularity.desc',
            'include_adult' => false,
            'include_video' => false,
            'page' => 1,
            'per_page' => 10 // Limitiamo a 10 film per categoria
        ]);

        if ($response->successful() && $response->json()) {
            $data = $response->json();
            cache()->put($cacheKey, $data, 1800); // Cache per 30 minuti
            return $data;
        }

        return null;
    }

    public function getShowFromGenre(string $genreId)
    {
        // Cache per 30 minuti
        $cacheKey = "shows_genre_{$genreId}";
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/discover/tv", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'with_genres' => $genreId,
            'sort_by' => 'popularity.desc',
            'include_adult' => false,
            'page' => 1,
            'per_page' => 10 // Limitiamo a 10 serie per categoria
        ]);

        if ($response->successful() && $response->json()) {
            $data = $response->json();
            cache()->put($cacheKey, $data, 1800); // Cache per 30 minuti
            return $data;
        }

        return null;
    }

    public function getMovieDetails(int $id)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
        ]);

        if ($response->successful() && $response->json()) {
            return $response->json();
        }

        return null;
    }

    public function searchMovies(string $query, int $page = 1)
    {
        // Cache per 15 minuti per le ricerche
        $cacheKey = "search_movies_" . md5($query . $page);
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'query' => $query,
            'page' => $page,
            'include_adult' => false,
        ]);

        if ($response->successful() && $response->json()) {
            $data = $response->json();
            cache()->put($cacheKey, $data, 900); // Cache per 15 minuti
            return $data;
        }

        return null;
    }

    public function searchShows(string $query, int $page = 1)
    {
        // Cache per 15 minuti per le ricerche
        $cacheKey = "search_shows_" . md5($query . $page);
        $cached = cache()->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $response = Http::get("{$this->baseUrl}/search/tv", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'query' => $query,
            'page' => $page,
            'include_adult' => false,
        ]);

        if ($response->successful() && $response->json()) {
            $data = $response->json();
            cache()->put($cacheKey, $data, 900); // Cache per 15 minuti
            return $data;
        }

        return null;
    }

    /**
     * Metodo per ottenere tutti i dati in parallelo (per future implementazioni)
     */
    public function getPopularContentParallel()
    {
        $cacheKey = 'popular_content_parallel';
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        // Questo potrebbe essere implementato con Promise/Async in futuro
        $genres = $this->getPopularMovieGenres();
        $moviesByGenre = [];

        foreach ($genres as $genre) {
            $moviesData = $this->getMovieFromGenre($genre['id']);
            if ($moviesData && isset($moviesData['results'])) {
                $moviesByGenre[$genre['name']] = $moviesData['results'];
            }
        }

        $data = [
            'genres' => $genres,
            'moviesByGenre' => $moviesByGenre
        ];

        Cache::put($cacheKey, $data, 3600); // 1 ora
        return $data;
    }
}
<?php

namespace App\Services; // Assicurati che il namespace sia corretto

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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

    public function getMovieFromGenre(string $genreId)
    {
        $response = Http::get("{$this->baseUrl}/discover/movie", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'with_genres' => $genreId,
            'sort_by' => 'popularity.desc',
            'include_adult' => false,
            'include_video' => false,
            'page' => 1
        ]);

        if ($response->successful() && $response->json()) {
            return $response->json();
        }

        return null;
    }

    public function getShowFromGenre(string $genreId)
    {
        dd($genreId);
        $response = Http::get("{$this->baseUrl}/discover/tv", [
            'api_key' => $this->apiKey,
            'language' => 'it-IT',
            'with_genres' => $genreId,
            'sort_by' => 'popularity.desc',
            'include_adult' => false,
            'include_video' => false,
            'page' => 1
        ]);

        if ($response->successful() && $response->json()) {
            return $response->json();
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
}
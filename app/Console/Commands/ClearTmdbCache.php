<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearTmdbCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulisce la cache di TMDb per aggiornare i dati';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Pulizia cache TMDb...');

        // Pulisce tutti i pattern di cache TMDb
        $patterns = [
            'popular_movie_genres',
            'popular_show_genres',
            'movies_genre_*',
            'shows_genre_*',
            'search_movies_*',
            'search_shows_*'
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                // Per i pattern con wildcard, dobbiamo iterare su tutte le chiavi
                $keys = Cache::get('cache_keys', []);
                foreach ($keys as $key) {
                    if (str_starts_with($key, str_replace('*', '', $pattern))) {
                        Cache::forget($key);
                        $this->line("Rimossa cache: {$key}");
                    }
                }
            } else {
                Cache::forget($pattern);
                $this->line("Rimossa cache: {$pattern}");
            }
        }

        $this->info('Cache TMDb pulita con successo!');
        
        return Command::SUCCESS;
    }
} 
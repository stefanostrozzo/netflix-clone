@extends('layout')
@section('content')
<!-- Alpine.js CDN per la logica mostra/nascondi -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div class="w-full min-h-screen bg-black text-white pt-20 pb-10" x-data="{ showPlayer: false }">
    <div class="w-full mx-auto flex flex-col md:flex-row gap-8 bg-gray-900 bg-opacity-80 rounded-lg shadow-lg p-6 md:p-12">
        <!-- Poster -->
        <div class="flex-shrink-0 w-full md:w-1/4 flex justify-center items-start">
            @if ($movie['poster_path'])
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="rounded-lg shadow-lg w-40 md:w-48 object-cover">
            @else
                <div class="w-40 h-60 bg-gray-800 flex items-center justify-center text-gray-400 rounded-lg">
                    Nessuna immagine
                </div>
            @endif
        </div>
        <!-- Info e Player -->
        <div class="flex-1 flex flex-col justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-red-500">{{ $movie['title'] }}</h1>
                <div class="flex items-center space-x-4 mb-4">
                    @if (isset($movie['release_date']) && $movie['release_date'])
                        <span class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                    @endif
                    @if (isset($movie['vote_average']))
                        <span class="text-yellow-400 text-sm flex items-center">★ {{ number_format($movie['vote_average'], 1) }}/10</span>
                    @endif
                </div>
                @if (!empty($movie['overview']))
                    <p class="text-gray-300 mb-6">{{ $movie['overview'] }}</p>
                @endif
            </div>
            <!-- Pulsante Visualizza -->
            <div class="w-full mt-4">
                <button @click="showPlayer = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded text-lg transition-colors w-full md:w-auto">🎬 Visualizza</button>
            </div>
        </div>
    </div>
    <!-- Overlay Player Fullscreen con freccia back -->
    <template x-if="showPlayer">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95">
            <button @click="showPlayer = false" class="absolute top-6 left-8 text-white text-3xl font-bold bg-red-600 hover:bg-red-700 rounded-full w-12 h-12 flex items-center justify-center focus:outline-none">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'>
                    <path stroke-linecap='round' stroke-linejoin='round' d='M15 19l-7-7 7-7' />
                </svg>
            </button>
            <div class="w-full h-full flex items-center justify-center">
                <div class="w-full h-full flex items-center justify-center">
                    <iframe class="w-full h-full max-h-screen max-w-screen" src="https://vixsrc.to/movie/{{ $movie['id'] }}?autoplay=true&lang=it&primaryColor=B20710"
                        title="Video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </template>
    <div class="mt-8 text-center">
        <a href="{{ route('movies.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors">← Torna ai Film</a>
    </div>
</div>
@endsection
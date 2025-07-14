@extends('layout')
@section('content')
<style>
    .movie-card { transition: transform 0.3s ease; }
    .movie-card:hover { transform: scale(1.05); z-index: 10; }
    .hero-gradient { background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.9) 100%); }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5); }
</style>
<div class="w-full min-h-screen bg-black text-white">
    <!-- Hero Section -->
    <section class="relative h-56 md:h-72 w-full mt-16 mb-8">
        <div class="absolute inset-0 hero-gradient z-10"></div>
        <div class="w-full h-full bg-gradient-to-r from-red-900 to-black"></div>
        <div class="absolute bottom-0 left-0 z-20 p-6 md:p-12">
            <h2 class="text-2xl md:text-4xl font-bold mb-2">Benvenuto su CinemaHub</h2>
            <p class="text-md md:text-lg text-gray-300 mb-2">Scopri film e serie TV popolari, mischiati per categoria!</p>
        </div>
    </section>
    <div class="">
        @forelse($categories as $genre => $items)
            @if(count($items) > 0)
                <section class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl md:text-2xl font-bold flex items-center">{{ $genre }}</h3>
                    </div>
                    <div class="flex space-x-4 overflow-x-auto pb-4 custom-scrollbar">
                        @foreach($items as $item)
                            <div class="movie-card flex-shrink-0 w-40 md:w-48 bg-gray-800 rounded-lg shadow-lg overflow-hidden relative group flex flex-col">
                                <a href="{{ isset($item['title']) ? route('movies.show', $item['id']) : route('series.show', $item['id']) }}" class="block aspect-[2/3] relative">
                                    @if ($item['poster_path'] ?? false)
                                        <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}" alt="{{ $item['title'] ?? $item['name'] }}" class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-400">Nessuna immagine</div>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-center justify-center">
                                        <button class="opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 bg-red-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </a>
                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ isset($item['title']) ? 'bg-red-600/20 text-red-400' : 'bg-blue-600/20 text-blue-400' }}">
                                            {{ isset($item['title']) ? 'Film' : 'Serie TV' }}
                                        </span>
                                        @if (isset($item['release_date']) && $item['release_date'])
                                            <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($item['release_date'])->format('Y') }}</span>
                                        @elseif (isset($item['first_air_date']) && $item['first_air_date'])
                                            <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($item['first_air_date'])->format('Y') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-semibold mb-1 text-white line-clamp-2">{{ $item['title'] ?? $item['name'] }}</h3>
                                    @if (isset($item['vote_average']))
                                        <div class="flex items-center mb-2">
                                            <span class="text-yellow-400 text-sm mr-1">★</span>
                                            <span class="text-gray-400 text-sm">{{ number_format($item['vote_average'], 1) }}/10</span>
                                        </div>
                                    @endif
                                    <a href="{{ isset($item['title']) ? route('movies.show', $item['id']) : route('series.show', $item['id']) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block mt-auto transition-colors">Dettagli</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @empty
            <div class="text-gray-400 py-8">Nessun contenuto trovato.</div>
        @endforelse
    </div>
</div>
@endsection 
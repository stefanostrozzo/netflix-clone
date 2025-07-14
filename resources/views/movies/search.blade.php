@extends('layout')
@section('content')
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5); }
        .movie-card { transition: transform 0.3s ease; }
        .movie-card:hover { transform: scale(1.05); z-index: 10; }
        .hero-gradient { background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.9) 100%); }
    </style>

    <div class="w-full min-h-screen bg-black text-white">
        <!-- Hero Section -->
        <section class="relative h-56 md:h-72 w-full mt-16 mb-8">
            <div class="absolute inset-0 hero-gradient z-10"></div>
            <div class="w-full h-full bg-gradient-to-r from-red-900 to-black"></div>
            <div class="absolute bottom-0 left-0 z-20 p-6 md:p-12">
                <h2 class="text-2xl md:text-4xl font-bold mb-2">Risultati Ricerca</h2>
                <p class="text-md md:text-lg text-gray-300 mb-2">Hai cercato: <span class="text-red-500 font-semibold">"{{ $query }}"</span> @if($totalResults > 0)- Trovati {{ $totalResults }} risultati @endif</p>
            </div>
        </section>

        <!-- Form di ricerca -->
        <form action="{{ route('movies.search') }}" method="GET" class="max-w-xl mx-auto mb-8">
            <div class="flex">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cerca un film..." class="flex-1 px-4 py-2 border border-gray-600 rounded-l-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-red-500">
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-r-lg transition-colors">Cerca</button>
            </div>
        </form>

        @if (session('error'))
            <div class="max-w-2xl mx-auto px-6 mt-4">
                <div class="bg-red-600/90 text-white p-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($totalResults > 0)
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 pb-16">
                @foreach($movies as $movie)
                    <div class="movie-card bg-gray-800 rounded-lg shadow-lg overflow-hidden relative group flex flex-col">
                        <a href="{{ route('movies.show', $movie['id']) }}" class="block aspect-[2/3] relative">
                            @if ($movie['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-400">
                                    Nessuna immagine
                                </div>
                            @endif
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-center justify-center">
                                <button class="opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 bg-red-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </a>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="text-lg font-semibold mb-1 text-white line-clamp-2">{{ $movie['title'] }}</h3>
                            @if (isset($movie['release_date']) && $movie['release_date'])
                                <p class="text-gray-400 text-xs mb-1">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</p>
                            @endif
                            @if (isset($movie['vote_average']))
                                <div class="flex items-center mb-1">
                                    <span class="text-yellow-400 text-sm mr-1">★</span>
                                    <span class="text-gray-400 text-sm">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                </div>
                            @endif
                            <a href="{{ route('movies.show', $movie['id']) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block mt-auto transition-colors">Dettagli</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Paginazione -->
            @if($totalPages > 1)
                <div class="flex justify-center mt-8 space-x-4">
                    @if ($page > 1)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $page - 1]) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Pagina Precedente
                        </a>
                    @endif
                    <span class="text-lg text-gray-300 self-center">Pagina {{ $page }} di {{ $totalPages }}</span>
                    @if ($page < $totalPages)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $page + 1]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                            Pagina Successiva
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🔍</div>
                <h2 class="text-2xl font-bold text-gray-300 mb-2">Nessun risultato trovato</h2>
                <p class="text-gray-500 mb-6">Prova con un termine di ricerca diverso</p>
                <a href="{{ route('movies.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors">Torna ai Film</a>
            </div>
        @endif
    </div>
@endsection 
@extends('layout')
@section('content')
    <div class="container mx-auto p-4">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('movies.index') }}" 
                   class="text-red-500 hover:text-red-400 font-semibold flex items-center">
                    ← Torna ai Film
                </a>
            </div>
            <h1 class="text-3xl font-bold mb-4 text-center">Risultati Ricerca</h1>
            <p class="text-center text-gray-400 mb-4">
                Hai cercato: <span class="text-red-500 font-semibold">"{{ $query }}"</span>
                @if($totalResults > 0)
                    - Trovati {{ $totalResults }} risultati
                @endif
            </p>
            
            <!-- Form di ricerca -->
            <form action="{{ route('movies.search') }}" method="GET" class="max-w-md mx-auto mb-6">
                <div class="flex">
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="Cerca un film..." 
                           class="flex-1 px-4 py-2 border border-gray-600 rounded-l-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-red-500">
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-r-lg transition-colors">
                        Cerca
                    </button>
                </div>
            </form>
        </div>

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($totalResults > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($movies as $movie)
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:transform hover:scale-105 transition-transform duration-300">
                        @if ($movie['poster_path'])
                            <a href="{{ route('movies.show', $movie['id']) }}">
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                     alt="{{ $movie['title'] }}" 
                                     class="w-full h-96 object-cover">
                            </a>
                        @else
                            <div class="w-full h-96 bg-gray-700 flex items-center justify-center text-gray-400">
                                Nessuna immagine
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 text-white">{{ $movie['title'] }}</h3>
                            @if (isset($movie['release_date']) && $movie['release_date'])
                                <p class="text-gray-400 text-sm mb-2">
                                    {{ \Carbon\Carbon::parse($movie['release_date'])->format('d/m/Y') }}
                                </p>
                            @endif
                            @if (isset($movie['vote_average']))
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-400 text-sm mr-1">★</span>
                                    <span class="text-gray-400 text-sm">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                </div>
                            @endif
                            <a href="{{ route('movies.show', $movie['id']) }}" 
                               class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block mt-2 transition-colors">
                                Dettagli
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginazione -->
            @if($totalPages > 1)
                <div class="flex justify-center mt-8 space-x-4">
                    @if ($page > 1)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $page - 1]) }}" 
                           class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors">
                            Pagina Precedente
                        </a>
                    @endif
                    <span class="text-lg text-gray-300 self-center">Pagina {{ $page }} di {{ $totalPages }}</span>
                    @if ($page < $totalPages)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $page + 1]) }}" 
                           class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Pagina Successiva
                        </a>
                    @endif
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🔍</div>
                <h2 class="text-2xl font-bold text-gray-300 mb-2">Nessun risultato trovato</h2>
                <p class="text-gray-500 mb-6">Prova con un termine di ricerca diverso</p>
                <a href="{{ route('movies.index') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors">
                    Torna ai Film
                </a>
            </div>
        @endif
    </div>
@endsection 
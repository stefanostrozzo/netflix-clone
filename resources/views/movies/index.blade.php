@extends('layout')
@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Film per Categoria</h1>
        
        <!-- Form di ricerca -->
        <div class="max-w-md mx-auto mb-8">
            <form action="{{ route('movies.search') }}" method="GET" class="flex">
                <input type="text" 
                       name="q" 
                       placeholder="Cerca un film..." 
                       class="flex-1 px-4 py-3 border border-gray-600 rounded-l-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500">
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-r-lg transition-colors duration-200">
                    🔍 Cerca
                </button>
            </form>
        </div>

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @foreach($genres as $genre)
            <div class="mb-8 relative group">
                <h2 class="text-2xl font-bold mb-4 text-red-500">{{ $genre['name'] }}</h2>
                
                <!-- Pulsante Sinistro -->
                <button onclick="scrollLeft('{{ $genre['name'] }}')" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 bg-black/80 hover:bg-red-600 text-white p-4 rounded-r-lg z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-lg hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Container Film con Scrollbar Nascosta -->
                <div id="scroll-{{ $genre['name'] }}" class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide">
                    @forelse($moviesByGenre[$genre['name']] ?? [] as $movie)
                        <div class="flex-none w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
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
                                <a href="{{ route('movies.show', $movie['id']) }}" 
                                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block mt-2">
                                    Dettagli
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">Nessun film trovato per questa categoria.</p>
                    @endforelse
                </div>

                <!-- Pulsante Destro -->
                <button onclick="scrollRight('{{ $genre['name'] }}')" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 bg-black/80 hover:bg-red-600 text-white p-4 rounded-l-lg z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-lg hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        @endforeach

        <div class="flex justify-center mt-8 space-x-4">
            @if ($page > 1)
                <a href="{{ route('movies.index', ['page' => $page - 1]) }}" 
                   class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Pagina Precedente
                </a>
            @endif
            <span class="text-lg text-gray-300 self-center">Pagina {{ $page }} di {{ min($totalPages, 500) }}</span>
            @if ($page < $totalPages && $page < 500)
                <a href="{{ route('movies.index', ['page' => $page + 1]) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Pagina Successiva
                </a>
            @endif
        </div>
    </div>

    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>

    <script>
        function scrollLeft(genreName) {
            const container = document.getElementById(`scroll-${genreName}`);
            container.scrollBy({
                left: -800,
                behavior: 'smooth'
            });
        }

        function scrollRight(genreName) {
            const container = document.getElementById(`scroll-${genreName}`);
            container.scrollBy({
                left: 800,
                behavior: 'smooth'
            });
        }
    </script>
@endsection
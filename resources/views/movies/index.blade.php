<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Popolari - Il tuo Netflix Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Film Popolari</h1>

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($movies as $movie)
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col">
                    @if ($movie['poster_path'])
                        <a href="{{ route('movies.show', $movie['id']) }}">
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-auto object-cover">
                        </a>
                    @else
                        <div class="w-full h-80 bg-gray-700 flex items-center justify-center text-gray-400">Nessuna immagine</div>
                    @endif
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h2 class="text-xl font-semibold mb-2 text-red-500">{{ $movie['title'] }}</h2>
                            @if (isset($movie['release_date']) && $movie['release_date'])
                                <p class="text-gray-400 text-sm mb-2">Data di uscita: {{ \Carbon\Carbon::parse($movie['release_date'])->format('d/m/Y') }}</p>
                            @endif
                            <p class="text-gray-300 text-sm line-clamp-3">{{ $movie['overview'] }}</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('movies.show', $movie['id']) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block">
                                Dettagli
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-400">Nessun film trovato.</p>
            @endforelse
        </div>

        <div class="flex justify-center mt-8 space-x-4">
            @if ($page > 1)
                <a href="{{ route('movies.index', ['page' => $page - 1]) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Pagina Precedente
                </a>
            @endif
            <span class="text-lg text-gray-300 self-center">Pagina {{ $page }} di {{ min($totalPages, 500) }}</span> {{-- TMDB spesso limita a 500 pagine --}}
            @if ($page < $totalPages && $page < 500)
                <a href="{{ route('movies.index', ['page' => $page + 1]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Pagina Successiva
                </a>
            @endif
        </div>
    </div>
</body>
</html>
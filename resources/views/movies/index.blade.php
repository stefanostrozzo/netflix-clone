@extends('layout')
@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Film per Categoria</h1>
        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @foreach($genres as $genre)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4 text-red-500">{{ $genre['name'] }}</h2>
                <div class="flex overflow-x-auto space-x-4 pb-4">
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
@endsection
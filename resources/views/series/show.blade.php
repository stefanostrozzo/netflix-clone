@extends('layout')
@section('content')
<div class="w-full min-h-screen bg-black text-white pt-20 pb-10">
    <div class="w-full flex flex-col md:flex-row gap-8 bg-gray-900 bg-opacity-80 rounded-lg shadow-lg min-h-[600px]">
        <!-- Colonna sinistra: Poster -->
        <div class="flex-shrink-0 w-full md:w-1/4 flex flex-col h-full justify-start items-center">
            @if ($show['poster_path'])
                <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}" alt="{{ $show['name'] }}" class="rounded-lg shadow-lg w-40 md:w-full max-h-64 aspect-[2/3] object-contain h-auto mb-4">
            @else
                <div class="w-40 h-60 bg-gray-800 flex items-center justify-center text-gray-400 rounded-lg mb-4">
                    Nessuna immagine
                </div>
            @endif
        </div>
        <!-- Info -->
        <div class="flex-1 flex flex-col justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-red-500">{{ $show['name'] }}</h1>
                <div class="flex items-center space-x-4 mb-4">
                    @if (isset($show['first_air_date']) && $show['first_air_date'])
                        <span class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                    @endif
                    @if (isset($show['vote_average']))
                        <span class="text-yellow-400 text-sm flex items-center">★ {{ number_format($show['vote_average'], 1) }}/10</span>
                    @endif
                </div>
                @if (!empty($show['overview']))
                    <p class="text-gray-300 mb-6">{{ $show['overview'] }}</p>
                @endif
                <div class="flex flex-col sm:flex-row gap-4 mb-4">
                    <div>
                        <label for="season" class="block mb-1 font-semibold">Stagione</label>
                        <select id="season" class="bg-gray-800 text-white rounded px-3 py-2" onchange="updateEpisodes()">
                            @foreach($seasons as $season)
                                <option value="{{ $season['season_number'] }}">{{ $season['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="episode" class="block mb-1 font-semibold">Episodio</label>
                        <select id="episode" class="bg-gray-800 text-white rounded px-3 py-2">
                            @foreach($episodes as $ep)
                                <option value="{{ $ep['episode_number'] }}">Ep. {{ $ep['episode_number'] }} - {{ $ep['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Player dedicato sotto -->
    <div class="w-full max-w-5xl mx-auto mt-8">
        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-lg border border-gray-800">
            <iframe id="player" class="w-full h-[70vh] min-h-[500px] max-h-[900px]" src="https://vixsrc.to/tv/{{ $show['id'] }}/1/1?autoplay=false&lang=it&primaryColor=B20710&autoplay=false"
                title="Video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('series.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors">← Torna alle Serie</a>
    </div>
    <script>
        // Aggiorna la lista episodi quando cambia la stagione
        function updateEpisodes() {
            const season = document.getElementById('season').value;
            fetch(`/api/series/{{ $show['id'] }}/season/${season}`)
                .then(res => res.json())
                .then(data => {
                    const episodeSelect = document.getElementById('episode');
                    episodeSelect.innerHTML = '';
                    data.episodes.forEach(ep => {
                        const opt = document.createElement('option');
                        opt.value = ep.episode_number;
                        opt.textContent = `Ep. ${ep.episode_number} - ${ep.name}`;
                        episodeSelect.appendChild(opt);
                    });
                    episodeSelect.removeEventListener('change', updatePlayer);
                    episodeSelect.addEventListener('change', updatePlayer);
                    updatePlayer();
                });
        }
        // Aggiorna il player quando cambia episodio
        document.getElementById('episode').addEventListener('change', updatePlayer);
        function updatePlayer() {
            const season = document.getElementById('season').value;
            const episode = document.getElementById('episode').value;
            document.getElementById('player').src = `https://vixsrc.to/tv/{{ $show['id'] }}/${season}/${episode}?autoplay=false&lang=it&primaryColor=B20710&autoplay=false`;
        }
    </script>
</div>
@endsection
@extends('layout')
@section('content')
    <!-- Custom Styles -->
    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Navbar transition */
        .navbar {
            transition: all 0.5s ease;
        }
        
        /* Card hover effect */
        .show-card {
            transition: transform 0.3s ease;
        }
        .show-card:hover {
            transform: scale(1.05);
            z-index: 10;
        }
        
        /* Hero gradient */
        .hero-gradient {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.9) 100%);
        }
        
        /* Pulse animation for buttons */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        .pulse-btn:hover {
            animation: pulse 0.5s ease-in-out;
        }
    </style>

    <div class="w-full min-h-screen bg-black text-white">
        <!-- Hero Section -->
        <section class="relative h-64 md:h-80 w-full mt-16">
            <div class="absolute inset-0 hero-gradient z-10"></div>
            <div class="w-full h-full bg-gradient-to-r from-blue-900 to-black"></div>
            <div class="absolute bottom-0 left-0 z-20 p-6 md:p-12">
                <h2 class="text-3xl md:text-5xl font-bold mb-2">Scopri Serie TV Incredibili</h2>
                <p class="text-lg md:text-xl text-gray-300 mb-4">Migliaia di serie TV organizzate per categoria</p>
                <div class="flex space-x-3">
                    <button class="pulse-btn bg-white text-black px-6 py-2 rounded flex items-center font-semibold hover:bg-opacity-80 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        Inizia a Guardare
                    </button>
                    <button class="bg-gray-600 bg-opacity-70 text-white px-6 py-2 rounded flex items-center font-semibold hover:bg-opacity-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Scopri di Più
                    </button>
                </div>
            </div>
        </section>

        <!-- Error Message -->
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-6 mt-4">
                <div class="bg-red-600/90 text-white p-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- TV Show Categories -->
        <div class="relative z-30 -mt-10 pb-20">
            @foreach($genres as $genre)
                <section class="mb-10 px-4 md:px-12">
                    <!-- Category Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl md:text-2xl font-bold flex items-center">
                            {{ $genre['name'] }}
                            <span class="ml-3 px-2 py-1 bg-blue-600/20 text-blue-400 text-xs font-bold rounded-full">
                                {{ count($showsByGenre[$genre['name']] ?? []) }}
                            </span>
                        </h2>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">
                            Vedi tutti →
                        </a>
                    </div>

                    <!-- Shows Horizontal Scroll -->
                    <div class="relative">
                        <div class="flex space-x-4 overflow-x-auto pb-6 custom-scrollbar">
                            @forelse($showsByGenre[$genre['name']] ?? [] as $show)
                                <div class="show-card flex-shrink-0 w-40 md:w-48 rounded-lg overflow-hidden relative group">
                                    <!-- Show Poster -->
                                    <a href="{{ route('series.show', $show['id']) }}" class="block aspect-[2/3] relative">
                                        @if ($show['poster_path'])
                                            <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}" 
                                                 alt="{{ $show['name'] }}" 
                                                 class="w-full h-full object-cover"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full bg-gray-800 flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-center justify-center">
                                            <button class="opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </a>

                                    <!-- Show Info -->
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/80 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <h3 class="text-sm font-semibold text-white mb-1 line-clamp-2">{{ $show['name'] }}</h3>
                                        
                                        @if (isset($show['first_air_date']) && $show['first_air_date'])
                                            <div class="flex items-center text-gray-300 text-xs mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}
                                            </div>
                                        @endif

                                        <div class="flex space-x-2">
                                            <button class="flex-1 bg-white text-black text-xs font-medium py-1 px-2 rounded hover:bg-gray-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                                Play
                                            </button>
                                            <button class="w-8 h-6 bg-gray-800/80 rounded flex items-center justify-center hover:bg-gray-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex-shrink-0 w-40 md:w-48 h-64 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs">Nessuna serie trovata</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center pb-12 px-4">
            <div class="flex items-center space-x-4">
                @if ($page > 1)
                    <a href="{{ route('series.index', ['page' => $page - 1]) }}" 
                       class="bg-gray-800/50 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Precedente</span>
                    </a>
                @endif

                <div class="bg-gray-800/30 px-4 py-3 rounded-lg backdrop-blur-sm">
                    <span class="text-gray-300 text-sm">
                        Pagina <span class="text-white font-semibold">{{ $page }}</span> di <span class="text-white font-semibold">{{ min($totalPages, 500) }}</span>
                    </span>
                </div>

                @if ($page < $totalPages && $page < 500)
                    <a href="{{ route('series.index', ['page' => $page + 1]) }}" 
                       class="bg-gray-800/50 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm">
                        <span>Successiva</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Mobile Search Button -->
        <div class="md:hidden fixed bottom-6 right-6 z-50">
            <button id="searchBtn" class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center shadow-xl hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>

        <!-- Mobile Search Modal -->
        <div id="searchModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <form action="{{ route('series.search') }}" method="GET" class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="q" 
                           placeholder="Cerca serie TV..." 
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg py-3 pl-12 pr-12 text-white focus:outline-none focus:border-blue-500">
                    <button type="button" id="closeSearch" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile search functionality
        const searchBtn = document.getElementById('searchBtn');
        const searchModal = document.getElementById('searchModal');
        const closeSearch = document.getElementById('closeSearch');
        
        if (searchBtn && searchModal && closeSearch) {
            searchBtn.addEventListener('click', () => {
                searchModal.classList.remove('hidden');
                searchModal.classList.add('flex');
                searchModal.querySelector('input').focus();
            });
            
            closeSearch.addEventListener('click', () => {
                searchModal.classList.add('hidden');
                searchModal.classList.remove('flex');
            });
            
            // Close on backdrop click
            searchModal.addEventListener('click', (e) => {
                if (e.target === searchModal) {
                    searchModal.classList.add('hidden');
                    searchModal.classList.remove('flex');
                }
            });
        }
        
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                if (window.scrollY > 100) {
                    navbar.classList.add('bg-black');
                    navbar.classList.remove('bg-gradient-to-b', 'from-black', 'to-transparent');
                } else {
                    navbar.classList.remove('bg-black');
                    navbar.classList.add('bg-gradient-to-b', 'from-black', 'to-transparent');
                }
            }
        });
    </script>
@endsection
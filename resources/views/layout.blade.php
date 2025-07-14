<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white font-sans antialiased">

    <header class="navbar fixed top-0 w-full z-50 bg-gradient-to-b from-black to-transparent px-4 md:px-12 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/">
                <div class="flex items-center">
                    <h1 class="text-2xl md:text-3xl font-bold text-red-600">CinemaHub</h1>
                </div>
            </a>
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6 mr-6">
                <a href="{{ route('movies.index') }}" class="text-gray-300 hover:text-white transition-colors font-medium flex items-center {{ request()->routeIs('movies.*') ? 'text-red-500 border-b-2 border-red-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M6 4h12M4 16h16" />
                    </svg>
                    Film
                </a>
                <a href="{{ route('series.index') }}" class="text-gray-300 hover:text-white transition-colors font-medium flex items-center {{ request()->routeIs('series.*') ? 'text-red-500 border-b-2 border-red-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Serie TV
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:block relative">
                    <form action="{{ route('movies.search') }}" method="GET" class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                               name="q" 
                               placeholder="Cerca film, serie TV..." 
                               class="bg-black bg-opacity-50 border border-gray-600 rounded-md py-2 pl-10 pr-4 text-sm focus:outline-none focus:border-white w-40 md:w-60">
                    </form>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-300 hover:text-white p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-gray-800">
            <div class="flex flex-col space-y-4 pt-4">
                <a href="{{ route('movies.index') }}" class="text-gray-300 hover:text-white transition-colors font-medium flex items-center py-2 {{ request()->routeIs('movies.*') ? 'text-red-500 bg-red-500/10 rounded-lg' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M6 4h12M4 16h16" />
                    </svg>
                    Film
                </a>
                <a href="{{ route('series.index') }}" class="text-gray-300 hover:text-white transition-colors font-medium flex items-center py-2 {{ request()->routeIs('series.*') ? 'text-red-500 bg-red-500/10 rounded-lg' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Serie TV
                </a>
                <div class="border-t border-gray-700 pt-4">
                    <form action="{{ route('movies.search') }}" method="GET" class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                               name="q" 
                               placeholder="Cerca film, serie TV..." 
                               class="w-full bg-gray-800 border border-gray-600 rounded-md py-2 pl-10 pr-4 text-sm focus:outline-none focus:border-white">
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Questo è dove il contenuto specifico di ogni vista verrà iniettato --}}
    <main class="min-h-screen">
        <div class="container-fluid h-100">
            @if (session('error'))
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Questo è il footer che sarà presente su tutte le pagine --}}
    <footer class="bg-gray-800 mt-8 py-4 text-center text-gray-400">
        <p>&copy; {{ date('Y') }} Netflix Clone. Tutti i diritti riservati.</p>
    </footer>

    <!-- Mobile Menu JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
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

    @yield('scripts')
</body>
</html>
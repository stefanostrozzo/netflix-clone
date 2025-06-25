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
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <h1 class="text-2xl md:text-3xl font-bold text-red-600">CinemaHub</h1>
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
                
                <!-- Profile -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded overflow-hidden bg-red-600 flex items-center justify-center">
                        <span class="text-xs font-bold">U</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </header>

    {{-- Questo è dove il contenuto specifico di ogni vista verrà iniettato --}}
    <main class="min-h-screen">
        <div class="container-fluid h-100 mx-auto px-4">
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

    @yield('scripts')
</body>
</html>
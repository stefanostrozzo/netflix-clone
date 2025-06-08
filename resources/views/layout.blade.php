<!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>FlixVault</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-900 text-white font-sans antialiased">

        {{-- Questa è la barra di navigazione che sarà presente su tutte le pagine --}}
        <nav class="bg-gray-800 p-4 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-white text-2xl font-bold">Netflix Clone</a>
                <div>
                    <a href="{{ route('movies.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Film Popolari</a>
                    <a href="{{ route('movies.top_rated') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Film Più Votati</a>
                    {{-- Puoi aggiungere altri link qui --}}
                </div>
            </div>
        </nav>

        {{-- Questo è dove il contenuto specifico di ogni vista verrà iniettato --}}
        <main class="min-h-screen">
            <div class="container-fluid h-100 mx-auto px-4">
                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4">
                        {{ session('success') }}
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
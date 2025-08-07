<!-- resources/views/components/app-layout.blade.php -->
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>DocOrient</title>
        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body class="flex flex-col min-h-screen bg-gray-100">

        <header class="bg-white shadow py-4 px-6">
            @isset($header)
                {{ $header }}
            @else
                <livewire:layout.navigation />
            @endisset
        </header>

        <main class="flex-grow flex px-6 py-8 h-auto">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t mt-12">
            @isset($footer)
                {{ $footer }}
            @else
                <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} DocOrient. Tous le pouvoir aux Soviets.
                </div>
            @endisset
        </footer>

        @vite('resources/js/app.js')
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>
</html>

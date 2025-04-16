<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion DocOrient</title>
    @vite('resources/css/app.css') {{-- si tu utilises Vite --}}
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white shadow-md rounded">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>

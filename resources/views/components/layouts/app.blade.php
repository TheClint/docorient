<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DocOrient</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
    {{ $slot }}

    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>

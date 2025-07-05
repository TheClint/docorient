<div>

    <x-flash-messages />

    <h1 class="text-xl font-bold mb-4">Liste des sessions</h1>

    <!-- Sessions en cours -->
    <div class="my-8">
        <h2 class="text-lg font-semibold text-green-700 mb-2">Sessions en cours</h2>
        <div class="flex flex-wrap gap-4">
            @forelse($sessionsEnCours as $session)
                <div class="w-80 rounded overflow-hidden shadow-lg bg-white p-6 flex flex-col justify-between">
                    <div class="flex flex-col space-y-2">

                        <h2 class="text-xl font-bold text-gray-800 break-words">{{ $session->nom }}</h2>
                        <h3 class="text-x2 font-bold text-gray-800 break-words">{{ $session->groupe->nom }}</h3>

                        @if($session->lieu)
                            <p class="text-sm break-words">lieu :
                                @if(Str::startsWith($session->lieu, ['http://', 'https://']))
                                    <a href="{{ $session->lieu }}" target="_blank" class="text-blue-600 underline">
                                        {{ $session->lieu }}
                                    </a>
                                @else
                                    {{ $session->lieu }}
                                @endif
                            </p>
                        @endif

                        @if($session->ouverture)
                            <p class="text-gray-800 text-sm">
                                <strong>Ouverture :</strong> {{ \Carbon\Carbon::parse($session->ouverture)->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if($session->fermeture)
                            <p class="text-gray-800 text-sm">
                                <strong>Fermeture :</strong> {{ \Carbon\Carbon::parse($session->fermeture)->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if($session->documents->count())
                            <div class="text-sm text-gray-700 mt-2">
                                <strong>Documents :</strong>
                                <ul class="list-disc list-inside">
                                    @foreach ($session->documents as $document)
                                        <li class="m-1">
                                            <a href="{{ route('documents.read', $document->id) }}" class="text-blue-600 hover:underline">
                                                {{ $document->nom }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        @if($session->user_id == Auth::id())
                                <x-button route="{{ route('sessions.president', $session->id) }}" label="Présider" />
                                <x-button class="ml-4" route="{{ route('sessions.edit', $session->id) }}" label="Gérer" />
                        @else
                            <x-button route="{{ route('sessions.membre', $session->id) }}" label="Participer" />
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucune session en cours.</p>
            @endforelse
        </div>
    </div>

    <!-- Sessions futures -->
    <div class="my-8">
        <h2 class="text-lg font-semibold text-blue-700 mb-2">Sessions futures</h2>
        <div class="flex flex-wrap gap-4">
            @forelse($sessionsFutur as $session)
                <div class="w-80 rounded overflow-hidden shadow-lg bg-white p-6 flex flex-col">
                    <div class="flex flex-col space-y-2">

                        <h2 class="text-xl font-bold text-gray-800 break-words">{{ $session->nom }}</h2>
                        <h3 class="text-x2 font-bold text-gray-800 break-words">{{ $session->groupe->nom }}</h3>

                        @if($session->lieu)
                            <p class="text-sm break-words">lieu :
                                @if(Str::startsWith($session->lieu, ['http://', 'https://']))
                                    <a href="{{ $session->lieu }}" target="_blank" class="text-blue-600 underline">
                                        {{ $session->lieu }}
                                    </a>
                                @else
                                    {{ $session->lieu }}
                                @endif
                            </p>
                        @endif

                        @if($session->ouverture)
                            <p class="text-gray-800 text-sm">
                                <strong>Ouverture prévue :</strong> {{ \Carbon\Carbon::parse($session->ouverture)->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if($session->documents->count())
                            <div class="text-sm text-gray-700 mt-2">
                                <strong>Documents :</strong>
                                <ul class="list-disc list-inside">
                                    @foreach ($session->documents as $document)
                                        <li class="m-1">
                                            <a href="{{ route('documents.read', $document->id) }}" class="text-blue-600 hover:underline">
                                                {{ $document->nom }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucune session future.</p>
            @endforelse
        </div>
    </div>

    <!-- Sessions passées -->
    <div class="my-8">
        <h2 class="text-lg font-semibold text-red-700 mb-2">Sessions passées</h2>
        <div class="flex flex-wrap gap-4">
            @forelse($sessionsPasse as $session)
                <div class="w-80 rounded overflow-hidden shadow-lg bg-white p-6 flex flex-col">
                    <div class="flex flex-col space-y-2">

                        <h2 class="text-xl font-bold text-gray-800 break-words">{{ $session->nom }}</h2>
                        <h3 class="text-x2 font-bold text-gray-800 break-words">{{ $session->groupe->nom }}</h3>

                        @if($session->lieu)
                            <p class="text-sm break-words">lieu :
                                @if(Str::startsWith($session->lieu, ['http://', 'https://']))
                                    <a href="{{ $session->lieu }}" target="_blank" class="text-blue-600 underline">
                                        {{ $session->lieu }}
                                    </a>
                                @else
                                    {{ $session->lieu }}
                                @endif
                            </p>
                        @endif

                        @if($session->ouverture)
                            <p class="text-gray-800 text-sm">
                                <strong>Ouverture :</strong> {{ \Carbon\Carbon::parse($session->ouverture)->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if($session->fermeture)
                            <p class="text-gray-800 text-sm">
                                <strong>Fermeture :</strong> {{ \Carbon\Carbon::parse($session->fermeture)->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if($session->documents->count())
                            <div class="text-sm text-gray-700 mt-2">
                                <strong>Documents :</strong>
                                <ul class="list-disc list-inside">
                                    @foreach ($session->documents as $document)
                                        <li class="m-1">
                                            <a href="{{ route('documents.read', $document->id) }}" class="text-blue-600 hover:underline">
                                                {{ $document->nom }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucune session passée.</p>
            @endforelse
        </div>
    </div>

</div>

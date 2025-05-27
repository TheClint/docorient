<div wire:poll.3s="poll" class="flex flex-col w-screen justify-between min-h-[70vh]">

    {{-- 🔹 Titre de la session --}}
    <h2 class="text-3xl font-bold text-center mb-6">
        Session : {{ $session->nom }}
    </h2>

    {{-- 🔹 Sous-cadre fixe pour l'état de la session --}}
    <div class="mx-auto w-full max-w-7xl h-[60vh] bg-white shadow rounded-2xl p-6 overflow-y-auto">
        @if ($documentEnCours)
            @if ($amendementEnCours)

                @if (is_null($session->amendement_id))
                    {{-- Étape 1 : Choix du document --}}
                    @if (is_null($documentEnCours))
                        <livewire:sessions.choix-document :session-id="$session->id" />
                    @else
                        {{-- Étape 2 : Choix de l’amendement dans le document sélectionné --}}
                        <livewire:amendements.index :document-id="$documentEnCours" mode="president" />
                    @endif
                @else
                    {{-- Étape 3 : Contrôle du vote sur l’amendement sélectionné --}}
                    @if ($amendementEnCours)
                        <div>
                            <h3 class="text-xl font-semibold mb-2">
                                📝 Amendement #{{ $amendementEnCours->id }}
                            </h3>
                            <p class="text-gray-700 mb-4">{{ $amendementEnCours->description }}</p>
        
                            @if ($voteEnCours)
                                <div class="text-yellow-700 font-bold">
                                    🕒 Vote en cours — Clôture à :
                                    {{ \Carbon\Carbon::parse($amendementEnCours->vote_fermeture)->format('H:i:s') }}
                                </div>
                            @elseif ($voteTermine && $resultatVote)
                                <div class="text-green-700 font-bold">
                                    ✅ Vote terminé : <span class="uppercase">{{ $resultatVote }}</span>
                                </div>
        
                                <button wire:click="passerAmendementSuivant"
                                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    ➡️ Passer à l'amendement suivant
                                </button>
                            @else
                                <button wire:click="lancerVote"
                                        class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    🟢 Lancer le vote
                                </button>
                            @endif
                        </div>
                    @else
                        <p class="text-red-600">Aucun amendement trouvé.</p>
                    @endif
                @endif
            @else
                <livewire:amendements.index :document-id="$documentEnCours" mode="president" />
            @endif
        @else
            <livewire:sessions.choix-document :session-id="$session->id" />
        @endif
    </div>

</div>

<div wire:poll.3s="poll" class="p-6 space-y-6">
    
    <h2 class="text-2xl font-bold">{{ $session->nom }}</h2>

    <div>
        @if($documentEnCours)
            <livewire:amendements.index :document-id="$documentEnCours" mode="president" />
            @if($amendementEnCours)
                <div class="p-4 bg-white rounded shadow">
                    <h3 class="text-xl font-semibold">Amendement #{{ $amendementEnCours->id }}</h3>
                    <p>{{ $amendementEnCours->description }}</p>

                    @if($voteEnCours)
                        <div class="mt-4 text-yellow-600 font-bold">
                            🕒 Vote en cours... Clôture à {{ $amendementEnCours->vote_fermeture->format('H:i:s') }}
                        </div>
                    @elseif($voteTermine && $resultatVote)
                        <div class="mt-4 text-green-700 font-bold">
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
            @endif
        @else
            <livewire:sessions.choix-document :session-id="$session->id" />
        @endif
    </div>

</div>

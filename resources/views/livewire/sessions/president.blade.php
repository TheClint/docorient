<div class="flex flex-col w-screen justify-between min-h-[70vh]">

    {{-- 🔹 Titre de la session --}}
    <h2 class="text-3xl font-bold text-center mb-6">
        Session : {{ $session->nom }} 
    </h2>
    {{-- 🔹 Sous-cadre fixe pour l'état de la session --}}
    <div wire:poll.3s="poll" class="mx-auto w-full max-w-7xl h-[60vh] bg-white shadow rounded-2xl p-6 overflow-y-auto">
        @if ($documentEnCours)
            @if ($amendementEnCours)
                @if($amendementEnCours->statut->libelle === "non voté")
                    <livewire:amendements.read :amendement="$amendementEnCours" mode="session"/>
                @else
                    <x-button route="" label="suivant" wire:click="passerAmendementSuivant()" />
                @endif
            @else
                <livewire:amendements.tableau-index :document="$documentEnCours" mode="session"/>
            @endif
        @else
            <livewire:sessions.choix-document :session-id="$session->id" />
        @endif
    </div>

</div>

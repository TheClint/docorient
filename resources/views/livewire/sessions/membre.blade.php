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
                <div class="flex w-full h-full">
                    {{-- 🟢 Résultat du vote à gauche --}}
                    <div class="flex-1 h-full">
                        <livewire:amendements.resultat :amendementId="$amendementEnCours->id" />
                    </div>
                </div>                
                @endif
            @else
                En attente de l'amendement à traiter
            @endif
        @else
            <p>En attente du document à traiter</p>
        @endif
    </div>

</div>

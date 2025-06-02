<div class="flex flex-col w-full justify-between min-h-[70vh]">

    

    <div>
        <x-flash-messages />
        <h2 class="text-2xl font-semibold m-3">
             <span>{{ $document->nom }}</span>
        </h2>
        <h3 class="m-3">Liste des amendements</h3>

        <livewire:amendements.tableau-index :document="$document"/>

    </div>

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('documents.read', $document->id) }}" label="Retour au texte" />
        </div>
    </div>
</div>

<div class="space-y-4 w-full flex flex-col" style="min-height: calc(70vh)">
    <x-flash-messages />
    <div class="flex flex-col space-around">
        <div class="mt-4 flex flex-col">
            <label for="groupe">Sélectionner un groupe</label>
            <select class="max-w-[15em] my-4" wire:model.live="selectedGroupeId" id="groupe">
                <option value="">-- Choisissez un groupe --</option>
                @foreach($groupes as $groupe)
                    <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                @endforeach
            </select>
        
            @if ($selectedGroupe)
                <div class="mt-4">
                    <h2>{{ $selectedGroupe->nom }}</h2>
                    <p>{{ $selectedGroupe->membres->count() }} membres</p>
                    <button wire:click="viewGroupe">Voir le groupe</button>
                </div>
            @endif
        </div>
    
        <div>
            <x-button route="{{ route('groupes.create') }}" label="Créer un nouveau groupe" />
        </div>
    </div>
    
</div>

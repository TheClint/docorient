<div class="space-y-4 w-full flex flex-col" style="min-height: calc(70vh)">
    <x-flash-messages />
    <div class="flex flex-col space-y-4">
        
        <div class="mt-4 flex flex-col">
            <label for="groupe">Sélectionner un groupe :</label>
        
            <div class="mt-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                <select class="max-w-[15em] my-2 md:my-4" wire:model.live="selectedGroupeId" id="groupe">
                    <option value="">-- Choisissez un groupe --</option>
                    @foreach($groupes as $groupe)
                        <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                    @endforeach
                </select>
            
                <x-button route="{{ route('groupes.create') }}" label="Créer un nouveau groupe" />
            </div>
            
        </div>

            @if ($selectedGroupe)
                <div class="m-4 flex flex-col items-start">
                    <h2>{{ $selectedGroupe->nom }}</h2>
                    <p>{{ $selectedGroupe->membres->count() }} membres</p>
                    <x-button route="{{ route('groupes.read', $selectedGroupe->id) }}" label="Voir le groupe" />
                </div>
            @endif
        
    </div>
    
</div>

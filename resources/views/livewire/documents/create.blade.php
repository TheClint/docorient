<div>
    
    <x-flash-messages />

    <h1 class="text-xl font-bold mb-4">Créer un nouveau document</h1>


    <!-- Formulaire de création -->
    <form wire:submit="save" class="space-y-4">
        <div>
            <label for="nom" class="block">Nom du document</label>
            <input type="text" id="nom" wire:model.live="nom" class="border p-1 w-full">
            @error('nom') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block">Description</label>
            <textarea id="description" wire:model.live="description" class="border p-1 w-full"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="contenu" class="block">Contenu</label>
            <textarea id="contenu" wire:model.live="contenu" class="border p-1 w-full"></textarea>
            @error('contenu') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="amendement_ouverture" class="block">Amendement ouverture</label>
            <input type="datetime-local" id="amendement_ouverture" wire:model.live="amendement_ouverture" class="border p-1 w-full" />
            @error('amendement_ouverture') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="vote_fermeture" class="block">Vote fermeture</label>
            <input type="datetime-local" id="vote_fermeture" wire:model.live="vote_fermeture" class="border p-1 w-full" />
            @error('vote_fermeture') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-red-500 text-white px-4 py-2">Créer</button>
    </form>
</div>

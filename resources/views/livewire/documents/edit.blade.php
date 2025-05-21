<div class="space-y-4 w-full flex flex-col justify-between" style="min-height: calc(70vh)">

    <x-flash-messages />
    
    <h2 class="text-xl font-bold mb-4">Modifier le document</h2>

    <form wire:submit="update" class="space-y-4">
        <div>
            <label for="nom" class="block">Nom</label>
            <input type="text" id="nom" wire:model.live="nom" class="border p-1 w-full">
            @error('nom') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block">Description</label>
            <textarea id="description" wire:model.live="description" class="border p-1 w-full"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
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

        <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105">Mettre à jour</button>
    </form>
</div>

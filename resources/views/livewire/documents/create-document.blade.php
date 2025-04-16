<div>
    <h1 class="text-xl font-bold mb-4">Créer un nouveau document</h1>

    <!-- Affichage du message de succès -->
    @if (session()->has('success'))
        <div class="bg-green-200 p-2 mb-4">{{ session('success') }}</div>
    @endif

    <!-- Formulaire de création -->
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label for="nom" class="block">Nom du document</label>
            <input type="text" id="nom" wire:model="nom" class="border p-1 w-full">
            @error('nom') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block">Description</label>
            <textarea id="description" wire:model="description" class="border p-1 w-full"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Créer</button>
    </form>
</div>

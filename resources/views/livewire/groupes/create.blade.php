<div class="space-y-4 w-full flex flex-col" style="min-height: calc(70vh)">
   <div class="m-6 flex flex-col"> 
    <x-flash-messages />

    <h2 class="text-xl font-bold mb-4">Créer un nouveau groupe</h2>

    <!-- Formulaire de création -->
    <form wire:submit="save" class="space-y-4">

        <div class="max-w-[30em]">
            <label for="nom" class="block mb-2">Nom du groupe</label>
            <input type="text" id="nom" wire:model.live="nom" class="border p-1 w-full">
            @error('nom') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-2 pt-4">
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105">Créer le groupe</button>
        </div>

    </form>
</div>
</div>

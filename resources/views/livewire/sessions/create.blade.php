<div class="space-y-4 w-full flex flex-col justify-between" style="min-height: calc(70vh)">
    
    <x-flash-messages />

    <h2 class="text-xl font-bold mb-4">Créer une nouvelle session</h2>


    <!-- Formulaire de création -->
    <form wire:submit="save" class="space-y-4">

        <div>
            <label for="nom" class="block">nom</label>
            <input type="text" id="nom" wire:model.live="nom" class="border p-1 w-full">
            @error('nom') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="lieu" class="block">Lieu (physique ou adresse visio)</label>
            <input type="text" id="lieu" wire:model.live="lieu" class="border p-1 w-full">
            @error('lieu') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="groupe" class="block">Choisir le groupe</label>
            <select wire:model.live="groupe" id="groupe" name="groupe" class="border p-1 w-full">
                <option value="">-- Sélectionner --</option>
                @foreach($groupes as $g)
                    <option value="{{ $g->id }}">{{ $g->nom }}</option>
                @endforeach
            </select>
        </div>  

        <div>
            <label for="president" class="block">Choisir la présidence</label>
            <select wire:model="president" id="president" name="president" class="border p-1 w-full">
                <option value="">-- Sélectionner --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('president') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="ouverture" class="block">Début de la session</label>
            <input type="datetime-local" id="ouverture" wire:model.live="ouverture" class="border p-1 w-full" />
            @error('ouverture') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="fermeture" class="block">fermeture</label>
            <input type="datetime-local" id="fermeture" wire:model.live="fermeture" class="border p-1 w-full" />
            @error('fermeture') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105">Créer</button>
    </form>
</div>

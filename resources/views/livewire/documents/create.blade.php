<div class="space-y-4 w-full flex flex-col justify-between" style="min-height: calc(70vh)">
    
    <x-flash-messages />

    <h2 class="text-xl font-bold mb-4">Créer un nouveau document</h2>

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

        <div class="flex items-center space-x-4">
            <span class="font-medium">Mode :</span>
        
            <span class="text-sm font-semibold {{ $automatique ? 'text-gray-500' : 'text-black' }}">
                Manuel
            </span>
        
            <!-- Custom iOS-style toggle switch -->
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="automatique" class="sr-only peer">
                <div class="w-14 h-7 bg-gray-300 rounded-full peer  transition duration-300"></div>
                <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-all duration-300 transform peer-checked:translate-x-7"></div>
            </label>
        
            <span class="text-sm font-semibold {{ $automatique ? 'text-black' : 'text-gray-500' }}">
                Automatique
            </span>
        </div>

        <div class="h-[100px]">
            <!-- Zone conditionnelle : MANUEL -->
            @if(!$automatique)
                <div class="flex space-x-2 pt-4">
                    <div>
                        <label for="session" class="block">Choisir la session</label>
                        <select wire:model="session" id="session" name="session" class="border p-1 w-full">
                            <option value="">-- Sélectionner --</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->nom }} du {{ $session->ouverture->setTimezone('Europe/Paris')->format('d/m/Y à H:i') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <x-button route="{{ route('sessions.create') }}" label="Créer une session" />
                    </div>
                </div>
            @endif

            <!-- Zone conditionnelle : AUTOMATIQUE -->
            @if($automatique)
                <div>
                    <label for="vote_fermeture" class="block">Vote fermeture</label>
                    <input type="datetime-local" id="vote_fermeture" wire:model.live="vote_fermeture" class="border p-1 w-full" />
                    @error('vote_fermeture') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif
        </div>

        <div>
            <label for="amendement_ouverture" class="block">Amendement ouverture</label>
            <input type="datetime-local" id="amendement_ouverture" wire:model.live="amendement_ouverture" class="border p-1 w-full" />
            @error('amendement_ouverture') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-2 pt-4">
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105">Créer le document</button>
        </div>
    </form>
</div>

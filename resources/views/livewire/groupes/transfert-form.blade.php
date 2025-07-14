<div>
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Recherche d'utilisateur --}}
        <div>
            <label for="searchUser">Mandataire</label>
            <input type="text"
                   wire:model.live.debounce.500ms="searchUser"
                   id="searchUser"
                   placeholder="Nom ou prénom…"
                   class="border rounded p-2 w-full" />

            @error('user_id_to') <span class="text-red-600">{{ $message }}</span> @enderror

            @if($searchResults->isNotEmpty())
                <ul class="border mt-1 rounded shadow bg-white">
                    @foreach ($searchResults as $user)
                        <li class="p-2 hover:bg-gray-100 cursor-pointer"
                            wire:click="selectUser({{ $user->id }})">
                            {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($user_id_to)
                <p class="text-red-600 mt-1">
                    Mandataire sélectionné : <strong>{{ $searchUser }}</strong>
                </p>
            @endif
        </div>

        {{-- Toggle rouge pour le type --}}
        <div>
            <label class="block mb-1">Type de transfert</label>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" wire:model.live="type" value="delegation" class="hidden">
                    <div class="px-4 py-2 rounded border"
                         @class([
                            'bg-red-600 text-white' => $type === 'delegation',
                            'bg-white text-black' => $type !== 'delegation'
                         ])>
                        Délégation
                    </div>
                </label>

                <label class="flex items-center cursor-pointer">
                    <input type="radio" wire:model.live="type" value="procuration" class="hidden">
                    <div class="px-4 py-2 rounded border"
                         @class([
                            'bg-red-600 text-white' => $type === 'procuration',
                            'bg-white text-black' => $type !== 'procuration'
                         ])>
                        Procuration
                    </div>
                </label>
            </div>
            @error('type') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Date de fin --}}
        <div>
            <label for="fin_at">Fin du mandat</label>
            <input type="date"
                   wire:model.live="fin_at"
                   id="fin_at"
                   min="{{ now()->toDateString() }}"
                   class="border p-2 rounded w-full">
            @error('fin_at') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
            Valider
        </button>
    </form>
</div>

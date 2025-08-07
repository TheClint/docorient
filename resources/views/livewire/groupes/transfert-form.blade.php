<div>
    <x-flash-messages />
    <form wire:submit.prevent="save" class="space-y-6">

        <div>
            <label for="theme" class="block">Choisir le thème</label>
            <select wire:model.live="themeId" id="theme" name="themeId" class="border p-1 w-full">
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}">{{ $theme->nom }}</option>
                @endforeach
            </select>

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
                <p class="mt-3">
                    Mandataire sélectionné : <strong class="text-red-600">{{ $searchUser }}</strong>
                </p>
            @endif
        </div>

        {{-- Toggle rouge pour le type --}}
        <div>
            <label class="block mb-1">Type de transfert</label>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" wire:model.live="type" value="delegation" class="hidden">
                    <div class="px-4 py-2 rounded border transition-all duration-150 {{ $type === 'delegation' ? 'bg-red-600 text-white' : 'bg-white text-black' }}">
                        Délégation
                    </div>
                </label>

                <label class="flex items-center cursor-pointer">
                    <input type="radio" wire:model.live="type" value="procuration" class="hidden">
                    <div class="px-4 py-2 rounded border transition-all duration-150 {{ $type === 'procuration' ? 'bg-red-600 text-white' : 'bg-white text-black' }}">
                        Procuration
                    </div>
                </label>
            </div>
            @error('type') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Date de fin --}}
        <div>
            <label for="fin_at">Fin du mandat (optionnel en cas de procuration)</label>
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

<div class="space-y-4 w-full flex flex-col" style="min-height: calc(70vh)">

    
    <div>
        <h2 class="text-xl m-4 font-semibold">Session : {{$session->nom}}</h2>
        <div class="flex flex-row w-full justify-around">
            <div>
                <h3 class="text-xl m-4 font-semibold">Commissaires actuels</h3>
                <ul class="space-y-2">
                    @foreach ($commissaires as $commissaire)
                        <li class="flex items-center justify-between bg-gray-100 p-2 rounded">
                            <div class="m-2" >
                                <strong>{{ $commissaire['name'] }}</strong> — {{ $commissaire['email'] }}
                            </div>
                            <x-button class="m-2" wire:click="removeCommissaire({{ $commissaire['id'] }})" route="" label="Retirer" />
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="text-xl m-4 font-semibold">Ajouter un commissaire</h3>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher un utilisateur..."
                    class="w-full p-2 border rounded"
                />

                @if (!empty($searchResults))
                    <ul class="mt-2 border rounded bg-white">
                        @foreach ($searchResults as $user)
                            <li 
                                class="p-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center"
                                wire:click="addCommissaire({{ $user['id'] }})"
                            >
                                <div>{{ $user['name'] }} — {{ $user['email'] }}</div>
                                <span class="text-red-600 ml-2 text-sm">Ajouter</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

</div>

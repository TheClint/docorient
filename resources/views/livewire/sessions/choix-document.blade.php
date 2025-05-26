<div>
    <div>
        @if($documents->isNotEmpty())
        <h2 class="my-2">Choisir le prochain document a amender</h2>
        <div class="flex gap-4">
            @foreach ($documents as $document)
                <div class="w-80 h-80 rounded overflow-hidden shadow-lg bg-white p-6 flex flex-col justify-between">
                    <div>
                        <div class="mb-4 flex flex-wrap justify-between items-end gap-y-1">
                            <h2 class="text-xl font-semibold text-gray-800 break-words">
                                {{ $document->nom }}
                            </h2>
                            <span class="text-sm text-gray-500 whitespace-nowrap ml-auto">
                                Créé le : {{ $document->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        @empty($document->description)
                            <p class="text-gray-400 italic">Aucune description disponible.</p>
                        @else
                            <p class="text-gray-700 text-sm line-clamp-4">
                                {{ $document->description }}
                            </p>
                        @endempty
                        
                    </div>

                    <div class="flex space-x-2 pt-4">
                        <x-button
                            route=""
                            label="Choisir"
                            wire:click="choixAmendementEnCours({{ $document->id }})"
                        />
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="p-4 bg-white rounded shadow text-gray-700">
                Tous les documents ont été traités.
            </div>
        @endif
    </div>
    
</div>

<div>

    <x-flash-messages />

    <h2 class="text-xl font-bold mb-4">Liste des documents</h2>

    <!-- Lien vers la page de création -->
    <div class = "my-8">
        <x-button route="{{ route('documents.create') }}" label="Créer un document" />
    </div>
    

    <!-- Liste des documents -->
    <div class="mt-4 flex flex-wrap gap-4">
        @forelse($documents as $document)

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
                    <x-button route="{{ route('documents.edit', $document->id) }}" label="Modifier" />
                    <x-button route="{{ route('documents.read', $document->id) }}" label="Lire" />
               </div>
            </div>

        @empty
            <p>Aucun document trouvé.</p>
        @endforelse
    </div>

</div>

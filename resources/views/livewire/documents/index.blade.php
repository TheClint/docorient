<div>

    <x-flash-messages />

    <h2 class="text-xl font-bold mb-4">Liste des documents</h2>

    <!-- Lien vers la page de création -->
    <div class="my-8">
        <x-button route="{{ route('documents.create') }}" label="Créer un document" />
    </div>

    <!-- Filtres -->
    <div class="flex flex-wrap gap-6 mb-6">
        <!-- Groupe -->
        <div class="w-64">
            <label for="groupe" class="block text-sm font-medium text-gray-700 mb-1">Groupe</label>
            <div class="relative">
                <select wire:model.live="selectedGroupe" id="groupe"
                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Tous les groupes</option>
                    @foreach($groupes as $groupe)
                        <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    
        <!-- Thème -->
        @if(!empty($themes))
        <div class="w-64">
            <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">Thème</label>
            <div class="relative">
                <select wire:model.live="selectedTheme" id="theme"
                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Tous les thèmes</option>
                    @foreach($themes as $theme)
                        <option value="{{ $theme->id }}">{{ $theme->nom }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
        @endif
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

    <!-- Pagination -->
    <div class="mt-6">
        {{ $documents->links() }}
    </div>

</div>

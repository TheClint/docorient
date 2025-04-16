<div>
    <h1 class="text-xl font-bold mb-4">Liste des documents</h1>

    <!-- Lien vers la page de création -->
    <a href="{{ route('documents.create') }}" class="text-blue-500 underline mb-4 inline-block">Créer un document</a>

    <!-- Liste des documents -->
    <ul class="mt-4 space-y-2">
        @forelse($documents as $document)
            <li class="border p-4">
                <strong>{{ $document->nom }}</strong><br>
                <span class="text-sm text-gray-500">Créé le : {{ $document->created_at->format('d/m/Y') }}</span><br>
                <p>{{ $document->description }}</p>

                <a href="{{ route('documents.edit', $document->id) }}" class="text-sm text-blue-500 underline">Modifier</a>
            </li>
        @empty
            <p>Aucun document trouvé.</p>
        @endforelse
    </ul>
</div>

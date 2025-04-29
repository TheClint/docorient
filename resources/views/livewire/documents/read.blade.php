<div>

    <x-flash-messages />

    <h1>{{ $document->nom }}</h1>
    <p>{{ $document->description }}</p>

    <h2>Contenu :</h2>
    <div>
        <p class="text-content leading-relaxed">
            {!! nl2br(e($texte)) !!}
        </p>
    </div>

    <a href="{{ route('documents.index') }}">Retour à la liste des documents</a>

    <div class="flex justify-end mb-4">
        <a href="{{ route('amendements.create', ['documentId' => $document->id]) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ✏️ Proposer un amendement
        </a>
    </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('amendements.index', ['documentId' => $document->id]) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ✏️ voir les amendements
        </a>
    </div>
    
</div>
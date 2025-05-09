<div class="flex flex-col w-screen" style="min-height: calc(70vh)">

    <x-flash-messages />

    <!-- Titre principal -->
    <h1 class="text-4xl font-bold mt-4">{{ $document->nom }}</h1>

    <!-- Description du document -->
    <p class="mt-2 text-lg text-gray-700">
        @if(empty($document->description))
            <i>Pas de description</i>
        @else
            <i>{{ $document->description }}</i>
        @endif
    </p>

    <!-- Contenu du document -->
    <div class="flex-grow">
        <p class="text-content leading-relaxed">
            {!! nl2br(e($texte)) !!}
        </p>
    </div>

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('documents.index') }}" label="Retour à la liste des documents" />
        </div>
    
        <!-- Boutons à droite -->
        <div class="flex gap-4">
            <x-button route="{{ route('amendements.create', ['documentId' => $document->id]) }}" label="Proposer un amendement" />
            <x-button route="{{ route('amendements.index', ['documentId' => $document->id]) }}" label="Voir les amendements" />
        </div>
    </div>
    
    
</div>

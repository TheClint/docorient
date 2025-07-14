<div class="flex flex-col w-screen" style="min-height: calc(70vh)">

    <x-flash-messages />

    <!-- Titre principal -->
    <h1 class="text-4xl font-bold mt-4">{{ $document->nom }}</h1>

    <!-- Description du document -->
    <p class="m-4 text-lg text-gray-700">
        @if(empty($document->description))
            <i>Pas de description</i>
        @else
            <i>{{ $document->description }}</i>
        @endif
    </p>

    @if ($comparerActive)
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-200 p-4">
                <h4 class="flex text-xl font-bold mb-2 justify-center">Texte final</h4>
                        {!! nl2br($formattedTexteFinalise) !!}
            </div>
            
            <div class="bg-gray-200 p-4">
                <h4 class="flex justify-center text-xl font-bold mb-2">Texte original</h4>
                        {!! nl2br($formattedTexte) !!}
            </div>
        </div>
    @else
        <!-- Contenu du document -->
        <div class="flex-grow">
            <p class="text-content leading-relaxed">
                {!! nl2br(e($estFinalise ? $texteFinalise : $texte)) !!}
            </p>
        </div>
    @endif

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('documents.index') }}" label="Retour à la liste des documents" />
        </div>
    
        <!-- Boutons à droite -->
        <div class="flex gap-4">
            @if ($estAmendable)
                <x-button route="{{ route('amendements.create', ['documentId' => $document->id]) }}" label="Proposer un amendement" />    
            @endif
            @if ($estFinalise)
                <x-button route="" wire:click="comparer()" label="Comparer" />   
            @endif
            @if ($fusionOuverte)
                <x-button route="{{ route('fusion.index', ['documentId' => $document]) }}" label="Fusionner" />   
            @endif
            <x-button route="{{ route('amendements.index', ['documentId' => $document->id]) }}" label="Voir les amendements" />
            
        </div>
    </div>  
</div>

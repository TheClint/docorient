
<div class="container">
    <x-flash-messages />

    <div class="flex flex-row justify-around m-4 mb-8">
        <div class="card mb-4">
            <div class="flex card-body flex-col justify-around h-full">
                <p><strong>Titre :</strong> {{ $document->nom }}</p>
                <p><strong>Auteur :</strong> {{ $amendement->user->name }}</p>
                <p><strong>Date de création :</strong> {{ $amendement->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut :</strong> {{ ucfirst($amendement->statut->libelle) }}</p>
                <p><strong>Commentaire :</strong> 
                    @if($amendement->commentaire)
                        {{ $amendement->commentaire }}
                    @else
                    Pas de commentaire
                    @endif
                </p>
            </div>
        </div>

        <livewire:amendements.vote :amendement="$amendement" />
    </div>


    <style>
        .diff {
            background-color: yellow;
            color: black;
            font-weight: bold;
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-200 p-4">
            <h4 class="flex text-xl font-bold mb-2 justify-center">Texte original</h4>
            @foreach ($segmentsAvant as $segment)
                @if($segment->texte[0] == "\n")
                    </span> 
                        @for ($i = 0; $i <= strlen($segment->texte); $i++)
                            <div class="w-full"><br></div>
                        @endfor
                    </span>
                @else
                    {!! nl2br(e($segment->texte)) !!}
                @endif
            @endforeach
            {!! $formattedTextOriginal !!}
            @foreach ($segmentsApres as $segment)
                @if($segment->texte[0] == "\n")
                    </span> 
                        @for ($i = 0; $i <= strlen($segment->texte); $i++)
                            <div class="w-full"><br></div>
                        @endfor
                    </span>
                @else
                    {!! nl2br(e($segment->texte)) !!}
                @endif
            @endforeach
        </div>
    
        <div class="bg-gray-200 p-4">
            <h4 class="flex justify-center text-xl font-bold mb-2">Texte amendé</h4>
            @foreach ($segmentsAvant as $segment)
                @if($segment->texte[0] == "\n")
                    </span> 
                        @for ($i = 0; $i <= strlen($segment->texte); $i++)
                            <div class="w-full"><br></div>
                        @endfor
                    </span>
                @else
                    {!! nl2br(e($segment->texte)) !!}
                @endif
            @endforeach
            {!! $formattedTextAmende !!}
            @foreach ($segmentsApres as $segment)
                @if($segment->texte[0] == "\n")
                    </span> 
                        @for ($i = 0; $i <= strlen($segment->texte); $i++)
                            <div class="w-full"><br></div>
                        @endfor
                    </span>
                @else
                    {!! nl2br(e($segment->texte)) !!}
                @endif
            @endforeach
        </div>
    </div>

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('amendements.index', ['documentId' => $document->id]) }}" label="Retour à la liste des amendements" />
        </div>
    
    </div>
</div>


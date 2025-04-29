</div>

<x-flash-messages />

<div class="container">
    <h1 class="mb-4">Consultation de l'Amendement</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations sur l'amendement</h5>
            <p><strong>Auteur :</strong> {{ $amendement->user->name }}</p>
            <p><strong>Date de création :</strong> {{ $amendement->created_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Statut :</strong> {{ ucfirst($amendement->statut->libelle) }}</p>
        </div>
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

    <div class="row">
        <div class="col-md-6">
            <h4>Texte original</h4>
            @foreach ($segmentsAvant as $segment)
                {{ $segment->texte }}
            @endforeach
            {!! $formattedTextOriginal !!}
            @foreach ($segmentsApres as $segment)
                {{ $segment->texte }}
            @endforeach
        </div>

        <div class="col-md-6">
            <h4>Texte amendé</h4>
            @foreach ($segmentsAvant as $segment)
                {{ $segment->texte }}
            @endforeach
            {!! $formattedTextAmende !!}
            @foreach ($segmentsApres as $segment)
                {{ $segment->texte }}
            @endforeach
        </div>
    </div>
</div>

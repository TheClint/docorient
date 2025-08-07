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
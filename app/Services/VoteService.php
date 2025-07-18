<?php

namespace App\Services;

use App\Models\User;
use App\Models\Statut;
use App\Models\Document;
use App\Models\Amendement;

class VoteService
{
    // fonction de comptabilisation d'un vote pour un amendement
    public static function comptabiliserVoteAmendement(Amendement $amendement): void
    {
        $amendement = Amendement::with('votes')->findOrFail($amendement->id);

        // récupération des membres du groupe
        $users = $amendement->getGroupe()->membres;

        // taux de majorité du vote (par défaut 50%)
        $tauxMajorite = 0.5; 

        $resultat = null;
        $compteurPour = 0;
        $compteurContre = 0;
        $compteurAbstention = 0;
        foreach($users as $user){
            $vote = $amendement->votes()->withPivot('approbation')->where('user_id', $user->id)->first()?->vote->approbation;
            if($vote === "pour")
                $compteurPour++;
            elseif($vote === "contre")
                $compteurContre++;
            elseif($vote === "abstention")
                $compteurAbstention++;
            else{
                if(false){
                    //prise en compte de la délégation de mandat dans le tableau résultat
                        // A faire
                }else{
                    // comptabilisation comme abstention
                    $amendement->votes()->syncWithoutDetaching([
                        $user->id => ['approbation' => 'abstention']
                    ]);
                    $compteurAbstention++;
                }       
            }
        }

        // pour éviter le divide by zero
        if($compteurPour + $compteurContre > 0){
            if($compteurPour / ($compteurPour + $compteurContre) > $tauxMajorite)
                $resultat = "adopté";
            else   
                $resultat = "rejeté";
        }
        else   
            $resultat = "rejeté";

        $amendement->statut_id = Statut::whereLibelle($resultat)->first()->id;
        
        $amendement->save();

        if($resultat == "adopté")
            TexteService::modificationDocument($amendement);  
    }

    // fonction de comptabilisation des votes pour tous les amendements d'un document
    public static function comptabiliserVoteDocument(Document $document): void
    {
        // Récupérer les segments de ce document
        $segmentIds = $document->segments->pluck('id');
        
        // Récupérer les amendements associés à ces segments
        $amendements = Amendement::whereHas('propositions', function ($query) use ($segmentIds) {
            $query->whereIn('segments.id', $segmentIds);
        })
        ->get();

        foreach($amendements as $amendement){
            VoteService::comptabiliserVoteAmendement($amendement);
        }
    }
}

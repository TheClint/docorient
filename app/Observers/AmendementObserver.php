<?php

namespace App\Observers;

use App\Models\Amendement;
use App\Jobs\ComptabiliserVoteAmendementJob;

class AmendementObserver
{
    
    /**
     * Handle the Amendement "created" event.
     */
    public function created(Amendement $amendement): void
    {
        //
    }

    /**
     * Handle the Amendement "updated" event.
     */
    public function updated(Amendement $amendement): void
    {
        //
    }

    public function saved(Amendement $amendement): void
    {
        // Si une date de fin de vote est présente

        if ($amendement->vote_fermeture) {
            
            // Calcul du délai en secondes
            $delayInSeconds = $amendement->vote_fermeture->timestamp - now()->timestamp;

            // Ne planifie que si la date est dans le futur
            if ($delayInSeconds > 0) {
                ComptabiliserVoteAmendementJob::dispatch($amendement)
                    ->delay(now()->addSeconds($delayInSeconds));
            }
        }
    }

    /**
     * Handle the Amendement "deleted" event.
     */
    public function deleted(Amendement $amendement): void
    {
        //
    }

    /**
     * Handle the Amendement "restored" event.
     */
    public function restored(Amendement $amendement): void
    {
        //
    }

    /**
     * Handle the Amendement "force deleted" event.
     */
    public function forceDeleted(Amendement $amendement): void
    {
        //
    }
}

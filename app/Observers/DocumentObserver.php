<?php

namespace App\Observers;

use App\Models\Document;
use App\Jobs\ComptabiliserVoteDocumentJob;
use Carbon\Carbon;

class DocumentObserver
{
   


    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        //
    }

    public function saved(Document $document): void
    {
        // Si une date de fin de vote est présente

        if ($document->vote_fermeture) {
            
            // Calcul du délai en secondes
            $delayInSeconds = $document->vote_fermeture->timestamp - now()->timestamp;

            // Ne planifie que si la date est dans le futur
            if ($delayInSeconds > 0) {
                ComptabiliserVoteDocumentJob::dispatch($document)
                    ->delay(now()->addSeconds($delayInSeconds));
            }
        }
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "restored" event.
     */
    public function restored(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "force deleted" event.
     */
    public function forceDeleted(Document $document): void
    {
        //
    }
}

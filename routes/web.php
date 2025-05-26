<?php

use App\Models\Document;

use App\Models\Amendement;
use App\Services\VoteService;

use App\Http\Middleware\IsPresident;
use Illuminate\Support\Facades\Route;

use App\Livewire\Documents\Create as DocumentsCreate;
use App\Livewire\Documents\Edit as DocumentsEdit;
use App\Livewire\Documents\Read as DocumentsRead;
use App\Livewire\Documents\Index as DocumentsIndex;

use App\Livewire\Amendements\Read as AmendementsRead;
use App\Livewire\Amendements\Index as AmendementsIndex;
use App\Livewire\Amendements\Create as AmendementsCreate;

use App\Livewire\Sessions\Create as SessionsCreate;
use App\Livewire\Sessions\Membre;
use App\Livewire\Sessions\President;

Route::view('/', 'welcome')->name('welcome');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    
    Route::middleware(['auth'])->group(function () {
        Route::get('/documents', DocumentsIndex::class)->name('documents.index');
        Route::get('/documents/ajouter', DocumentsCreate::class)->name('documents.create');
        Route::get('/documents/{document}', DocumentsRead::class)->name('documents.read');
        Route::get('/documents/{document}/modifier', DocumentsEdit::class)->name('documents.edit');
        
        Route::get('/documents/{documentId}/amendements', AmendementsIndex::class)->name('amendements.index');
        Route::get('/documents/{documentId}/amendements/create', AmendementsCreate::class)->name('amendements.create');
        Route::get('/amendements/{amendement}', AmendementsRead::class)->name('amendements.read');

        Route::get('/sessions/{sessionId}/president', President::class)->name('sessions.president')->middleware(['auth', IsPresident::class]);
        Route::get('/sessions/membre', Membre::class);

        Route::get('/sessions/ajouter', SessionsCreate::class)->name('sessions.create');
        
        // temporaire
        Route::get('/test/vote/{amendement}', function (Amendement $amendement) {
            VoteService::comptabiliserVoteAmendement($amendement);
                
            return 'Vote comptabilisé pour l’amendement #' . $amendement->id;
        })->name('test.vote');
        Route::get('/test/voteD/{document}', function (Document $document) {
            VoteService::comptabiliserVoteDocument($document);

            return 'Vote comptabilisé pour le document #' . $document->id;
        })->name('test.voteD');
        // fin Temporaire
});

require __DIR__.'/auth.php';

<?php

use App\Livewire\Actions\Logout;


use Illuminate\Support\Facades\Route;
use App\Livewire\Documents\Edit as DocumentsEdit;

use App\Livewire\Documents\Read as DocumentsRead;
use App\Livewire\Sessions\Index as SessionsIndex;
use App\Livewire\Documents\Index as DocumentsIndex;

use App\Livewire\Sessions\Membre;
use App\Livewire\Sessions\President;
use App\Livewire\Sessions\Create as SessionsCreate;
use App\Livewire\Sessions\Edit as SessionsEdit;

use App\Livewire\Amendements\Read as AmendementsRead;
use App\Livewire\Documents\Create as DocumentsCreate;
use App\Livewire\Amendements\Index as AmendementsIndex;
use App\Livewire\Amendements\Create as AmendementsCreate;

use App\Livewire\Fusion\Index as FusionIndex;
use App\Livewire\Fusion\Read as FusionRead;
use App\Livewire\Fusion\Create as FusionCreate;

use App\Livewire\Groupes\Create as GroupesCreate;
use App\Livewire\Groupes\Index as GroupesIndex;
use App\Livewire\Groupes\Read as GroupesRead;

Route::view('/', 'welcome')->name('welcome');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', function (Logout $logout) {
    $logout(); // appelle la méthode __invoke() de ta classe Logout

    return redirect('/'); // redirection après logout
    })
    ->middleware('auth')
    ->name('logout');

    
    Route::middleware(['auth'])->group(function () {
        Route::get('/documents', DocumentsIndex::class)->name('documents.index');
        Route::get('/documents/ajouter', DocumentsCreate::class)->name('documents.create');
        Route::get('/documents/{document}', DocumentsRead::class)->name('documents.read');
        Route::get('/documents/{document}/modifier', DocumentsEdit::class)->name('documents.edit');
        
        Route::get('/documents/{documentId}/amendements', AmendementsIndex::class)->name('amendements.index');
        Route::get('/documents/{documentId}/amendements/create', AmendementsCreate::class)->name('amendements.create');
        Route::get('/amendements/{amendement}', AmendementsRead::class)->name('amendements.read');

        Route::get('/documents/{documentId}/fusion', FusionIndex::class)->name('fusion.index');
        Route::get('fusion/{segmentId}/read', FusionRead::class)->name('fusion.read');
        Route::get('fusion/{segmentId}/create', FusionCreate::class)->name('fusion.create');

        Route::get('/sessions/{sessionId}/president', President::class)->name('sessions.president')->middleware(['auth', 'president', 'session.en.cours']);
        Route::get('/sessions/{sessionId}/membre', Membre::class)->name('sessions.membre')->middleware('session.en.cours');
        
        Route::get('/sessions/ajouter', SessionsCreate::class)->name('sessions.create');
        Route::get('/sessions', SessionsIndex::class)->name('sessions.index');
        Route::get('/sessions/{sessionId}/edit', SessionsEdit::class)->name('sessions.edit')->middleware(['auth', 'president', 'session.en.cours']);

        Route::get('/groupes/ajouter', GroupesCreate::class)->name('groupes.create');
        Route::get('/groupes', GroupesIndex::class)->name('groupes.index');
        Route::get('/groupes/{groupeId}', GroupesRead::class)->name('groupes.read');
});

require __DIR__.'/auth.php';

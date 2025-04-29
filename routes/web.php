<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Documents\Edit as DocumentsEdit;
use App\Livewire\Documents\Create as DocumentsCreate;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Documents\Read as DocumentsRead;

use App\Livewire\Amendements\Index as AmendementsIndex;
use App\Livewire\Amendements\Create as AmendementsCreate;
use App\Livewire\Amendements\Read as AmendementsRead;

Route::view('/', 'welcome');



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
});


require __DIR__.'/auth.php';

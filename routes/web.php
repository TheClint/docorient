<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Documents\EditDocument;
use App\Livewire\Documents\CreateDocument;
use App\Livewire\Documents\IndexDocuments;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', IndexDocuments::class)->name('documents.index');
    Route::get('/documents/ajouter', CreateDocument::class)->name('documents.create');
    Route::get('/documents/{document}/modifier', EditDocument::class)->name('documents.edit');
});



require __DIR__.'/auth.php';

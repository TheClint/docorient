
<div class="container">
    <x-button route="" label="test" wire:click="test({{ $amendement->id }})" />
    <x-flash-messages />
    <div class="flex flex-row justify-around m-4 mb-8">
        <livewire:amendements.vignette :document="$document" :amendement="$amendement" />

        <div class = "min-h-[200px]">
            @if ($president && $mode === "session" && $amendement->vote_fermeture == null)
            <div class="flex flex-col h-full justify-around">
                    <x-button route="" label="Mettre au vote" wire:click="mettreAuVote({{ $amendement->id }})" />
                    <x-button route="" label="retour" wire:click="retour()" />
            </div>
            @else
                <livewire:amendements.vote :amendement="$amendement" />
            @endif
        </div>
    </div>

    <livewire:amendements.comparaison-textes :amendement="$amendement" />

    @if($mode === "consultation")
        <div class="flex justify-between m-4">
            <!-- Bouton à gauche -->
            <div class="flex mr-4">
                <x-button route="{{ route('amendements.index', ['documentId' => $document->id]) }}" label="Retour à la liste des amendements" />
            </div>
        </div>
    @endif
</div>


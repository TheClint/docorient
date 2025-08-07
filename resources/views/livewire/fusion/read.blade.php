<div class="container mx-auto px-4 py-6">
    <h3 class="text-2xl font-bold text-center text-gray-800 mb-6">Fusion d'amendements</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Colonne gauche : Amendements adoptés -->
        <div class="space-y-6 flex flex-col">
            <h4 class="text-lg font-semibold text-center text-gray-700 mb-4">Amendements adoptes</h4>

            <div class="overflow-y-auto h-full space-y-6 flex flex-col justify-between">
                @foreach ($this->amendements as $index => $amendement)
                    <div class="bg-white shadow rounded-lg p-4 border border-gray-200 flex justify-between">
                        <div class="font-semibold">#{{ $index + 1 }}</div>

                        <div class="prose text-gray-800">
                            @if ($indexGauche === $index && $texteHighlightGauche)
                                {!! $texteHighlightGauche !!}
                            @else
                                {!! nl2br(e($amendement->texteReconstruit ?? '')) !!}
                            @endif
                        </div>
                        <div class="flex flex-col p-2 h-100 justify-center items-center">
                            <x-button class="m-2" wire:click="comparerTexte('gauche', {{ $index }})" route="" label="{{ $indexGauche === $index ? 'Masquer' : 'Comparer' }}" />
                            <x-button route="{{ route('amendements.read', ['amendement' => $amendement]) }}" label="Consulter" />
                        </div>
                    </div>
                @endforeach
                </div>
        </div>

        <!-- Colonne droite : Propositions de fusion -->
        <div class="space-y-6 flex flex-col">
            <h4 class="text-lg font-semibold text-center text-gray-700 mb-4">Propositions de fusion</h4>

            <div class="overflow-y-auto h-full space-y-6 flex flex-col justify-between">
                @foreach ($this->amendementsFusion as $index => $amendement)
                    <div class="bg-white shadow rounded-lg p-4 border border-gray-200 flex justify-between">
                        <div class="font-semibold">#{{ $index + 1 }}</div>

                        <div class="prose text-gray-800">
                            @if ($indexDroite === $index && $texteHighlightDroite)
                                {!! $texteHighlightDroite !!}
                            @else
                                {!! nl2br(e($amendement->texte ?? '')) !!}
                            @endif
                        </div>
                        <div class="flex flex-col p-2 h-100 justify-center items-center">
                            <x-button class="m-2" wire:click="comparerTexte('droite', {{ $index }})" route="" label="{{ $indexDroite === $index ? 'Masquer' : 'Comparer' }}" />
                            <x-button route="{{ route('amendements.read', ['amendement' => $amendement]) }}" label="Consulter" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 text-start">
        <x-button route="{{ route('fusion.index', ['documentId' => $this->document->id]) }}" label="Retour a la fusion" />
    </div>
</div>
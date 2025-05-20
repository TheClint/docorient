<div class="space-y-4 w-full flex flex-col justify-between" style="min-height: calc(70vh)">

    <x-flash-messages />
 
    <h3 class="text-lg font-semibold">Sélectionnez la plage de texte à amender :</h3>
    
    {{-- Segments affichés horizontalement --}}
    <div id="selectionZone" class="flex flex-wrap border bg-gray-50 leading-relaxed">
        @foreach ($segments as $segment)
            <span wire:click="selectSegment({{ $segment->id }})"
                class="cursor-pointer 
                    @if($segmentDebutId && $segmentFinId && $segment->id >= $segmentDebutId && $segment->id <= $segmentFinId)
                        bg-yellow-300
                    @elseif($segment->id === $segmentDebutId || $segment->id === $segmentFinId)
                        bg-yellow-400
                    @else
                        hover:bg-gray-200
                    @endif"
            >
                @if($segment->texte[0] == "\n")
                    </span> 
                        @for ($i = 0; $i <= strlen($segment->texte); $i++)
                            <div class="w-full"><br></div>
                        @endfor
                    </span>
                @else
                    {!! nl2br(e($segment->texte)) !!}
                @endif
            </span>
        @endforeach
    </div>

    {{-- Aperçu du texte sélectionné --}}
    @if ($texteModifiable)
        <div class="mt-2 p-2 bg-white border rounded">
            <strong class="block text-sm mb-1">Texte sélectionné :</strong>
            <p class="text-gray-700 italic">{{ $texteModifiable }}</p>
        </div>
    @endif

    {{-- Champ de modification --}}
    <div class="mt-4 space-y-2">
        <label class="block font-medium">Amendement :</label>
    
        {{-- Partie modifiable --}}
        <textarea wire:model.live="texteModifiable" class="w-full border rounded p-2" rows="4">
        </textarea>
    </div>

    <div class="mt-4 space-y-2">
        <label for="commentaire" class="block font-medium">Commentaire (optionnel) :</label>
        <textarea wire:model.live="commentaire" id="commentaire" rows="2" class="w-full border rounded p-2"></textarea>
    </div>

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('documents.read', ['document' => $documentId]) }}" label="Retour au document" />
        </div>
    
        <!-- Boutons à droite -->
        <div class="flex gap-4">
            <button wire:click="save"
                class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105">
                Proposer l'amendement
            </button>
        </div>
    </div>

</div>

@script
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const zone = document.getElementById('selectionZone');

            zone.addEventListener('mouseup', () => {
                const selection = window.getSelection();
                const selectedText = selection.toString().trim();

                if (selectedText.length > 0) {
                    // Envoie seulement le texte sélectionné à Livewire
                    @this.set('texteModifiable', selectedText);
                }
            });
        });
    </script>
@endscript

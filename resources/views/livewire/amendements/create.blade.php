<div class="space-y-4">

    <x-flash-messages />
 
    <h2 class="text-lg font-semibold">Sélectionnez une plage de texte à amender :</h2>
    
    {{-- Segments affichés horizontalement --}}
    <div id="selectionZone" class="flex flex-wrap border rounded bg-gray-50 leading-relaxed">
        @foreach ($segments as $segment)
            <span wire:click="selectSegment({{ $segment->id }})"
                class="cursor-pointer rounded 
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
                        @for ($i = 0; $i < strlen($segment->texte); $i++)
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
    @if ($segmentDebutId && $segmentFinId)
        <div class="mt-2 p-2 bg-white border rounded">
            <strong class="block text-sm mb-1">Texte sélectionné :</strong>
            <p class="text-gray-700 italic">{{ $texte }}</p>
        </div>
    @endif

    {{-- Champ de modification --}}
    <div class="mt-4 space-y-2">
        <label class="block font-medium">Amendement :</label>
    
        {{-- Partie avant : non éditable --}}
        <div class="w-full border rounded p-2 bg-gray-100 text-gray-500" style="white-space: pre-wrap;">
            {{ $texteAvant }}
        </div>
    
        {{-- Partie modifiable --}}
        <textarea wire:model="texteModifiable" class="w-full border rounded p-2" rows="4">
        </textarea>
    
        {{-- Partie après : non éditable --}}
        <div class="w-full border rounded p-2 bg-gray-100 text-gray-500" style="white-space: pre-wrap;">
            {{ $texteApres }}
        </div>
    </div>

    <div class="mt-2">
        <label for="commentaire" class="block font-medium">Commentaire (optionnel) :</label>
        <textarea wire:model="commentaire" id="commentaire" rows="2" class="w-full border rounded p-2"></textarea>
    </div>

    {{-- Boutons --}}
    <div class="flex gap-4 mt-4">
        <button wire:click="save"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Proposer l'amendement
        </button>
    
        <a href="{{ route('documents.read', ['document' => $documentId]) }}"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            ← Retour au document
        </a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const zone = document.getElementById('selectionZone');

        zone.addEventListener('mouseup', () => {
            const selection = window.getSelection();
            const selectedText = selection.toString();

            if (selectedText.length > 0) {
                const fullText = zone.textContent;
                const start = fullText.indexOf(selectedText);
                const end = start + selectedText.length;

                const texteAvant = fullText.substring(0, start);
                const texteModifiable = selectedText;
                const texteApres = fullText.substring(end);

                // Envoie les trois morceaux à Livewire
                @this.set('texteAvant', texteAvant.trim());
                @this.set('texteModifiable', texteModifiable.trim());
                @this.set('texteApres', texteApres.trim());
            }
        });
    });
</script>

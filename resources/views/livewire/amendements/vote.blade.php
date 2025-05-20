<div class="border p-4 rounded shadow max-w-sm">
    
    <div class="mb-4">
        <span class="font-semibold">État du vote :</span>
        @if ($etatVote == "attente")
            <span class="text-yellow-500 italic">en attente</span>
        @elseif ($etatVote == "ouvert")
            <span class="text-green-600 italic">ouvert</span>
        @elseif ($etatVote == "ferme")
            <span class="text-gray-500 italic">fermé</span>
        @endif
    </div> 

    @if ($prochaineEtape)
        <div
            id="compte-rebours"
            data-prochaine-etape="{{ \Carbon\Carbon::parse($prochaineEtape)->timestamp }}"
            class="text-sm text-gray-600 my-2"
        >
            Chargement du compte à rebours...
        </div>
    @endif



        {{-- Cas : vote ouvert (modifiable tant que ce n’est pas fermé) --}}
        <div class="flex flex-col gap-2">
            @foreach (['pour', 'contre', 'abstention'] as $choix)
                @php
                    $isSelected = ($voteSelectionne ?? 'abstention') === $choix;
                    $baseClasses = 'px-4 py-2 rounded w-full text-left transition';
                    $colors = [
                        'pour' => $isSelected ? 'bg-green-600 text-white' : 'hover:bg-green-200 text-gray-800',
                        'contre' => $isSelected ? 'bg-red-600 text-white' : 'hover:bg-red-200 text-gray-800',
                        'abstention' => $isSelected ? 'bg-gray-600 text-white' : 'hover:bg-gray-200 text-gray-800',
                    ];
                @endphp
                
                    @if ($etatVote == "ouvert")
                        <button
                            wire:click="voter('{{ $choix }}')"
                            class="{{ $baseClasses }} {{ $colors[$choix] }}">
                            {{ ucfirst($choix) }}
                        </button>    
                    @else
                        <button
                            class="{{ $baseClasses }} {{ $colors[$choix] }} cursor-not-allowed"
                            disabled>
                            {{ ucfirst($choix) }}
                        </button>
                    @endif
            @endforeach
        </div>

        @script
        <script>
            // Définir la fonction dans le scope global
            window.lancerCompteRebours = function () {
                const el = document.getElementById('compte-rebours');
                if (!el) return;
        
                const timestampCible = parseInt(el.dataset.prochaineEtape);
                if (isNaN(timestampCible)) {
                    el.textContent = 'Date invalide.';
                    return;
                }
        
                clearInterval(el._interval); // évite les doublons si la fonction est relancée
        
                function formatDuree(secondes) {
                    if (secondes <= 0) return "Terminé";
                    const h = String(Math.floor(secondes / 3600)).padStart(2, '0');
                    const m = String(Math.floor((secondes % 3600) / 60)).padStart(2, '0');
                    const s = String(secondes % 60).padStart(2, '0');
                    return `${h}:${m}:${s}`;
                }
        
                function updateAffichage() {
                    const maintenant = Math.floor(Date.now() / 1000);
                    const diff = timestampCible - maintenant;
                    const jour = 24*60*60;
                    el.textContent = diff < jour ? `Temps restant : ${formatDuree(diff)}` : `Temps restant : ` + Math.trunc(diff/jour) + " jour(s)";
                }
        
                updateAffichage();
                el._interval = setInterval(updateAffichage, 1000);
            };
        
            // Lance une fois au chargement DOM
            document.addEventListener('DOMContentLoaded', () => {
                window.lancerCompteRebours();
            });
        
            // Relance après chaque message Livewire traité
            if (window.Livewire) {
                window.Livewire.hook('message.processed', () => {
                    window.lancerCompteRebours();
                });
            }
        </script>
        @endscript
        


</div>



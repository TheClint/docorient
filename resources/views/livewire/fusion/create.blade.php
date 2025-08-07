<div class="container mx-auto px-4 py-6">
    <!-- Titre centré -->
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-center text-gray-800">Fusion d'amendements</h3>
    </div>

    <!-- Grille à deux colonnes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Colonne gauche : Amendements proposés -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-center text-gray-700">Amendements adoptés</h3>

            <div class="overflow-y-auto max-h-[55vh] space-y-6">
            @foreach ($amendements as $index => $amendement)
                <div class="bg-white shadow rounded-lg p-4 border border-gray-200 flex justify-between">
                    
                    <div class="mb-2 flex flex-col justify-between">
                        <span class="font-semibold text-gray-800">Amendement {{ $index + 1 }}</span>
                        
                        <div class="prose prose-sm max-w-none text-gray-800">
                            <div id="texte-reconstruit-{{ $index }}" class="texte-reconstruit">
                                {!! nl2br(e($amendement->texteReconstruit ?? '')) !!}
                            </div>

                            <div id="texte-highlight-{{ $index }}" class="texte-highlight hidden">
                                {!! $amendement->texteReconstruitHighlight ?? '' !!}
                            </div>
                        </div>

                        @if (!empty($amendement->commentaire))
                            <div class="mt-3 text-sm text-gray-600 italic border-t pt-2">
                                Commentaire : {{ $amendement->commentaire }}
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-center h-100 items-center">
                        <button
                            type="button"
                            onclick="toggleIndexCourant({{ $index }})"
                            class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105"
                            id="btn-compare-{{ $index }}"
                        >
                            Comparer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        </div>

        <!-- Colonne droite : Texte original + Formulaire -->
        <div class="flex flex-col justify-between space-y-6">
            <!-- Texte original -->
            <h3 class="text-lg font-bold text-center text-gray-800">Texte original</h3>

            <div class="flex flex-col justify-between h-full">
                <div class="bg-white p-4 rounded shadow border mb-4">

                    <div id="texte-original" class="prose prose-sm max-w-none text-gray-800">
                        {!! nl2br(e($texteOriginal)) !!}
                    </div>

                    @foreach ($amendements as $index => $amendement)
                        <div id="texte-original-highlight-{{ $index }}" 
                            class="prose prose-sm max-w-none text-gray-800 hidden"
                            data-index="{{ $index }}">
                            {!! $amendement->texteOriginalHighlight !!}
                        </div>
                    @endforeach
                </div>

                <!-- Formulaire -->
                <div class="bg-white p-4 rounded shadow border space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Proposer une fusion des amendements</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Texte :</label>
                        <textarea wire:model.defer="texteModifiable"
                                rows="5"
                                class="w-full border border-gray-300 rounded p-2 text-sm resize-y focus:outline-none focus:ring focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Commentaire (optionnel) :</label>
                        <textarea wire:model.defer="commentaire"
                                rows="2"
                                class="w-full border border-gray-300 rounded p-2 text-sm resize-y focus:outline-none focus:ring focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex justify-between">
                        <x-button route="{{route('fusion.index', ['documentId' => $this->document->id])}}" label="Retour à la fusion" />
                        <x-button wire:click="proposerAmendement" route="" label="Valider l'amendement" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

        let indexCourant = null;

        function toggleIndexCourant(index) {
            if (indexCourant === index) {
                indexCourant = null;
            } else {
                indexCourant = index;
            }
            // Après modification, tu peux appeler une fonction pour mettre à jour l'affichage
            updateCompareButtons();
            updateTextsDisplay();
            updateTexteOriginal();
        }

        function updateCompareButtons() {
            document.querySelectorAll('[id^="btn-compare-"]').forEach(button => {
                const btnIndex = parseInt(button.id.replace('btn-compare-', ''), 10);
                button.textContent = (btnIndex === indexCourant) ? 'Masquer' : 'Comparer';
            });
        }

        function updateTextsDisplay() {
            document.querySelectorAll('[id^="texte-reconstruit-"]').forEach(div => {
                const idx = parseInt(div.id.replace('texte-reconstruit-', ''), 10);
                div.classList.toggle('hidden', idx === indexCourant);
            });

            document.querySelectorAll('[id^="texte-highlight-"]').forEach(div => {
                const idx = parseInt(div.id.replace('texte-highlight-', ''), 10);
                div.classList.toggle('hidden', idx !== indexCourant);
            });
        }

        function updateTexteOriginal() {
            const original = document.getElementById('texte-original');
            const allHighlights = document.querySelectorAll('[id^="texte-original-highlight-"]');

            if (indexCourant === null) {
                original.classList.remove('hidden');
                allHighlights.forEach(el => el.classList.add('hidden'));
            } else {
                original.classList.add('hidden');
                allHighlights.forEach(el => {
                    const elIndex = parseInt(el.dataset.index, 10);
                    if (elIndex === indexCourant) {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            }
        }

</script>
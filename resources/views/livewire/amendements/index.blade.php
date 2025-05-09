<div class="flex flex-col w-screen justify-between min-h-[70vh]">

    

    <div>
        <x-flash-messages />
        <h2 class="text-2xl font-semibold m-3">
             <span>{{ $document->nom }}</span>
        </h2>
        <h3 class="m-3">Liste des amendements</h3>

        {{-- Tableau des amendements --}}
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        {{-- Colonnes triables --}}
                        <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('numero')">
                            <span class="font-medium">Numéro</span>
                            @if($sortField === 'numero')
                                <span class="text-sm">
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                </span>
                            @endif
                        </th>
                        <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('created_at')">
                            <span class="font-medium">Date de création</span>
                            @if($sortField === 'created_at')
                                <span class="text-sm">
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                </span>
                            @endif
                        </th>
                        <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('user')">
                            <span class="font-medium">Auteur</span>
                            @if($sortField === 'user')
                                <span class="text-sm">
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                </span>
                            @endif
                        </th>
                        <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('statut')">
                            <span class="font-medium">Statut</span>
                            @if($sortField === 'statut')
                                <span class="text-sm">
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                </span>
                            @endif
                        </th>
                        <th class="px-4 py-2">Commentaire</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amendements as $index => $amendement)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $numero[$amendement->id] }}</td> {{-- Numéro --}}
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($amendement->created_at)->format('d/m/Y H:i') }}</td> {{-- Date formatée --}}
                            <td class="px-4 py-2">{{ $amendement->user->name }}</td> {{-- Auteur --}}
                            <td class="px-4 py-2">{{ $amendement->statut->libelle }}</td> {{-- Statut --}}
                            <td class="px-4 py-2">
                                @if($amendement->commentaire)
                                    {{ \Str::limit($amendement->commentaire, 100) }} {{-- Affichage du commentaire avec un limite à 100 caractères --}}
                                @else
                                    Pas de commentaire
                                @endif
                            </td>
                            <td class="px-4 py-2 flex justify-center">
                                    <x-button route="{{ route('amendements.read', ['amendement' => $amendement->id]) }}" label="Consulter" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-between m-4">
        <!-- Bouton à gauche -->
        <div class="flex mr-4">
            <x-button route="{{ route('documents.read', $document->id) }}" label="Retour au texte" />
        </div>
    </div>
</div>

{{-- Script pour la gestion du tri --}}
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('sorted', function (field, direction) {
            // Optionnel: si tu veux manipuler des effets après le tri
        });
    });
</script>

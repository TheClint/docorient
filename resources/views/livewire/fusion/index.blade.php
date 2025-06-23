<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-4">Groupes de conflits d'amendements</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">amendements en conflit</th>
                    <th class="px-4 py-2">Partie en conflit</th>
                    <th class="px-4 py-2">taille du conflit (en caractère)</th>
                    <th class="px-4 py-2">Fusions déjà proposées</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groupesConnexes as $index => $groupe)
                    @php
                        $auteurs = $groupe['amendements']->pluck('user')->unique('id');
                        $userEstAuteur = $auteurs->pluck('id')->contains(auth()->id());
                    @endphp

                    <tr class="border-b">
                        <td class="px-4 py-2 text-center font-semibold">{{ $index + 1 }}</td>

                        <td class="px-4 py-2 text-center">
                            {{ count($groupe['amendements']) }}
                        </td>

                        <td class="relative px-4 py-2 text-sm max-w-xs truncate text-center group" title="{{ $groupe['texte_conflit'] }}">
                            {{ $groupe['texte_conflit'] }}
                            
                            <div class="absolute left-0 bottom-full mb-2 w-80 max-w-xs hidden group-hover:block whitespace-normal break-words bg-gray-700 text-white text-xs rounded px-2 py-1 z-50">
                                {{ $groupe['texte_conflit'] }}
                            </div>
                        </td>
                   
                        <td class="px-4 py-2 text-center">
                            {{ $groupe['taille_max_conflit'] }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ $groupe['fusions_count'] }}
                        </td>

                        <td class="px-4 py-2 text-center h-[65px]">
                            <div class="flex justify-center space-x-2">
                                <x-button
                                    label="Consulter"
                                    route="{{ route('fusion.read', $groupe['segment_ids'][0]) }}" 
                                />
                                @if (
                                    ($president && $mode === 'session') ||
                                    ($mode !== 'session' && $userEstAuteur)
                                )
                                    <x-button
                                        label="Proposer"
                                        route="{{ route('fusion.create', $groupe['segment_ids'][0]) }}" 
                                    />
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 italic text-gray-500">
                            Aucun groupe de conflits détecté pour ce document.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

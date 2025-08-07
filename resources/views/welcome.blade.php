@php
    $layout = Auth::check() ? 'app-layout' : 'guest-layout';
@endphp

<x-dynamic-component :component="$layout">
    
    <!-- Main content -->
    <div class="max-w-4xl w-full px-4 py-12 h-full">
        <section class="text-center">
            <h2 class="text-4xl font-bold mb-4">Collaborez sur vos documents d'orientation</h2>
            <p class="text-lg text-gray-700 mb-8">
                Créez, modifiez et votez les documents d'orientation avec votre communauté. Donnez du sens à la participation collective.
            </p>
            <a href="#" class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">Commencer</a>
        </section>

        <section class="mt-16 grid md:grid-cols-3 gap-8">
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-xl font-semibold text-red-600 mb-2">Créer un document</h3>
                <p class="text-gray-600">Initiez un document d’orientation structuré, prêt à être débattu.</p>
            </div>
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-xl font-semibold text-red-600 mb-2">Proposer des amendements</h3>
                <p class="text-gray-600">Ajoutez des suggestions ou corrections pour enrichir le document.</p>
            </div>
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-xl font-semibold text-red-600 mb-2">Voter collectivement</h3>
                <p class="text-gray-600">Chaque amendement peut être soumis à un vote clair et transparent.</p>
            </div>
        </section>
    </div>


    <!-- Footer -->
    <x-slot name="footer">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} DocOrient. Tous droits réservés.
        </div>
    </x-slot>

</x-dynamic-component>

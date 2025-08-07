
<div class="w-full h-full px-8 py-4 flex gap-8 items-stretch">
    {{-- 🟢 Graphique circulaire à gauche --}}
    <div 
        x-data="voteChart({ pour: {{ $compteurPour }}, contre: {{ $compteurContre }}, abstention: {{ $compteurAbstention }} })"
        x-init="init()"
        x-on:vote-completed.window="update($event.detail)"
        class="flex-1 flex justify-center items-center"
    >
        <canvas id="voteChart" class="w-full h-auto"></canvas>
    </div>


    {{-- 🔵 Compteurs au centre --}}
    <div class="flex flex-col justify-around flex-1">
        <h3 class="text-2xl font-bold text-gray-800 text-center">Résultat du vote</h3>

        <div class="flex justify-center items-center">
            @if ($compteurPour > $compteurContre)
                <p class="text-green-700 text-2xl font-semibold text-center">✅ Amendement adopté</p>
            @elseif ($compteurContre > $compteurPour)
                <p class="text-red-700 text-2xl font-semibold text-center">❌ Amendement rejeté</p>
            @else
                <p class="text-gray-700 text-2xl font-semibold text-center">⚖️ Égalité ou abstention majoritaire</p>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="bg-green-100 text-green-800 rounded-xl py-4 shadow">
                <p class="text-sm">Pour</p>
                <p class="text-2xl font-bold">{{ $compteurPour }}</p>
            </div>

            <div class="bg-red-100 text-red-800 rounded-xl py-4 shadow">
                <p class="text-sm">Contre</p>
                <p class="text-2xl font-bold">{{ $compteurContre }}</p>
            </div>

            <div class="bg-yellow-100 text-yellow-800 rounded-xl py-4 shadow">
                <p class="text-sm">Abstention</p>
                <p class="text-2xl font-bold">{{ $compteurAbstention }}</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function voteChart({ pour, contre, abstention }) {
            return {
                chart: null,

                init() {
                    this.render(pour, contre, abstention);
                },

                render(p, c, a) {
                    const ctx = document.getElementById('voteChart').getContext('2d');
                    
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Pour', 'Contre', 'Abstention'],
                            datasets: [{
                                data: [p, c, a],
                                backgroundColor: ['#22c55e', '#ef4444', '#facc15'],
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                },

                update({ pour, contre, abstention }) {
                    this.render(pour, contre, abstention);
                }
            }
        }
    </script>
@endpush

</div>




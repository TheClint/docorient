
<div class="w-full h-full px-8 py-4 flex gap-8 items-stretch">
    {{-- 🟢 Graphique circulaire à gauche --}}
    <div class="flex-1 flex justify-center items-center">
        <canvas id="voteChart" class=" w-full h-auto"></canvas>
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
        function renderVoteChart() {
            console.log("coucou");
            const canvas = document.getElementById('voteChart');

            const ctx = canvas.getContext('2d');

            window.voteChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pour', 'Contre', 'Abstention'],
                    datasets: [{
                        data: [{{ $compteurPour }}, {{ $compteurContre }}, {{ $compteurAbstention }}],
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
        }

        document.addEventListener('DOMContentLoaded', renderVoteChart);
        
    </script>
    @endpush
    
</div>




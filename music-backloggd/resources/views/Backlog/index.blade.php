@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-6">
        Meu Backlog de Avaliações
    </h1>

    <form method="GET" action="{{ route('backlog.index') }}"
          class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-8 flex flex-wrap gap-4 items-end">

        <div class="flex-1 min-w-[150px]">
            <label for="search" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Busca por música</label>
            <input id="search" type="text" name="search" value="{{ request('search') }}"
                   class="input w-full"
                   placeholder="Nome da música...">
        </div>

        <div class="flex-1 min-w-[120px]">
            <label for="rating" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nota</label>
            <select id="rating" name="rating" class="input w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800">
            <option value="">Todas as notas</option>
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(request('rating') == $i)>
                {{ $i }} estrela{{ $i > 1 ? 's' : '' }}
                </option>
            @endfor
            </select>
        </div>

        <div>
            <label for="date_from" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Data de</label>
            <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}" class="input">
        </div>

        <div>
            <label for="date_to" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Até</label>
            <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}" class="input">
        </div>

        <button type="submit"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
            Filtrar
        </button>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-green-600 dark:text-green-400 mb-4">Estatísticas</h2>
        <p class="text-lg mb-4">Média de nota: <span class="font-bold">{{ number_format($averageRating, 2) }}</span></p>
        <canvas id="ratingChart" class="w-full max-w-lg mx-auto" height="200"></canvas>
    </div>

    <div class="grid gap-4">
        @forelse ($reviews as $review)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex gap-4 items-center">
                <img src="{{ $review->track_image }}" alt="Capa de {{ $review->track_name }}"
                     class="w-16 h-16 rounded object-cover shadow">

                <div class="flex-1">
                    <h3 class="text-lg font-bold text-green-600 dark:text-green-400">
                        {{ $review->track_name }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-300 text-sm mt-1">
                        {{ $review->comment ?? 'Sem comentário.' }}
                    </p>
                    <div class="flex items-center mt-2" aria-label="Nota: {{ $review->rating }}">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                                 fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <polygon points="9.9,1.1 12.4,6.6 18.4,7.3 13.7,11.6 15.1,17.5 9.9,14.3 4.7,17.5 6.1,11.6 1.4,7.3 7.4,6.6"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                <span class="text-xs text-gray-400">
                    {{ $review->created_at->format('d/m/Y') }}
                </span>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400">
                Nenhuma review encontrada.
            </p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('ratingChart');
    if (!chartCanvas) return;
    const ctx = chartCanvas.getContext('2d');

    const labels = {!! json_encode($ratingDistribution->keys()) !!};
    const dataValues = {!! json_encode($ratingDistribution->values()) !!};

    console.log('Labels:', labels);
    console.log('Data:', dataValues);

    const data = {
        labels: labels,
        datasets: [{
            label: 'Quantidade de avaliações',
            data: dataValues,
            backgroundColor: 'rgba(34,197,94,0.6)',
            borderColor: 'rgba(34,197,94,1)',
            borderWidth: 1,
            borderRadius: 4,
        }]
    };

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} avaliação(s)`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,

                }
            }
        }
    });
});
</script>
@endpush

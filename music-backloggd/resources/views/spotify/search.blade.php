@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Buscar músicas</h1>

    <form method="GET" action="{{ route('spotify.search') }}" class="flex gap-2 mb-8">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Digite o nome da música"
            required
            class="flex-1 px-4 py-2 border rounded focus:outline-none focus:ring"
        >
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Buscar
        </button>
    </form>

    @if(!empty($results))
        <h2 class="text-xl font-semibold mb-4">Resultados:</h2>
        <ul>
            @foreach ($results as $track)
                <li class="mb-4">
                    <a href="{{ route('track.show', ['id' => $track['id']]) }}"
                       class="block p-4 bg-white dark:bg-gray-800 rounded shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center gap-4">
                            <img src="{{ $track['album']['images'][2]['url'] ?? 'https://via.placeholder.com/64' }}"
                                 alt="Album"
                                 class="w-16 h-16 rounded object-cover">
                            <div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $track['name'] }}
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ implode(', ', array_column($track['artists'], 'name')) }}
                                </p>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @elseif(request()->has('q'))
        <p class="text-gray-600 mt-6">Nenhuma música encontrada.</p>
    @endif
</div>
@endsection

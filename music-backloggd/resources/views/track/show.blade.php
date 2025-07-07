@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <a href="{{ route('home') }}" class="text-green-600 hover:underline flex items-center gap-1 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7"></path>
        </svg>
        Voltar para Dashboard
    </a>

    <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col md:flex-row gap-6 items-center">
        <img src="{{ $track['album']['images'][0]['url'] ?? 'https://via.placeholder.com/150' }}"
             alt="Capa do álbum"
             class="w-40 h-40 md:w-48 md:h-48 rounded-lg object-cover shadow-md">

        <div class="flex-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $track['name'] }}</h1>

            <p class="text-lg text-gray-700 mt-2">
                <span class="font-medium">Artistas:</span> {{ implode(', ', array_column($track['artists'], 'name')) }}
            </p>

            <p class="text-sm text-gray-500 mt-2">
                <span class="font-medium">Álbum:</span> {{ $track['album']['name'] }}
            </p>

            <a href="{{ $track['external_urls']['spotify'] }}"
               target="_blank"
               class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                Abrir no Spotify
            </a>
        </div>
    </div>
</div>
@endsection

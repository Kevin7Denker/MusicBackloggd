@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-green-400 via-blue-500 to-purple-600 rounded-xl shadow-lg mb-8 mt-8 p-8 flex flex-col items-center justify-center text-center max-w-4xl w-full mx-auto">
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-2 drop-shadow-lg">
        Bem-vindo ao Music Backloggd!
    </h1>
    <p class="text-lg md:text-xl text-white/90 mb-4">
        Descubra, acompanhe e organize suas músicas favoritas.
    </p>
</div>
<div class="max-w-7xl mx-auto px-2 py-6">
    <h2 class="text-3xl font-bold text-green-600 dark:text-green-400 border-b-2 border-green-500 pb-2 w-fit mb-6 text-left">
        Suas músicas ouvidas recentemente
    </h2>
    @if($recentTracks->isEmpty())
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6h13M9 6L5 9m4-3L5 3"></path>
            </svg>
            <p class="text-lg text-gray-500 dark:text-gray-400">
                Você ainda não ouviu músicas recentemente ou não conseguimos carregar.
            </p>
        </div>
    @else
        <div class="flex gap-6 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-green-400 scrollbar-track-green-100 dark:scrollbar-thumb-green-600 dark:scrollbar-track-gray-800">
            @foreach(collect($recentTracks)->take(6) as $item)
            @php
                $track = $item['track'];
                $imageUrl = $track['album']['images'][0]['url'] ?? 'https://via.placeholder.com/150';
                $artists = implode(', ', array_column($track['artists'], 'name'));
            @endphp

            <a href="{{ route('track.show', ['id' => $track['id']]) }}"
               class="flex-shrink-0 w-[161px] sm:w-[177px] md:w-[185px] bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group transform">
                <div class="relative">
                <img src="{{ $imageUrl }}"
                     alt="Capa do álbum"
                     class="w-full h-44 md:h-48 object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300">
                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full opacity-80 group-hover:opacity-100 transition-opacity">
                    {{ \Carbon\Carbon::parse($item['played_at'])->diffForHumans() }}
                </span>
                </div>
                <div class="p-4 flex flex-col gap-1">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $track['name'] }}">
                    {{ $track['name'] }}
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 truncate" title="{{ $artists }}">
                    {{ $artists }}
                </p>
                </div>
            </a>
            @endforeach
        </div>
    @endif
        @if($topTracks->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-3xl font-bold text-purple-600 dark:text-purple-400 border-b-2 border-purple-500 pb-2 w-fit mb-6">
            Suas músicas mais ouvidas
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($topTracks->take(8) as $track)
            @php
                $imageUrl = $track['album']['images'][0]['url'] ?? 'https://via.placeholder.com/150';
                $artists = implode(', ', array_column($track['artists'], 'name'));
            @endphp

            <a href="{{ route('track.show', ['id' => $track['id']]) }}"
               class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:shadow-2xl transition-all overflow-hidden group transform hover:-translate-y-1 hover:scale-105 duration-300 flex flex-col">
                <div class="relative">
                <img src="{{ $imageUrl }}"
                     alt="Capa do álbum"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300 rounded-t-2xl">
                <span class="absolute bottom-2 right-2 bg-purple-600 text-white text-xs px-2 py-1 rounded-full opacity-80 group-hover:opacity-100 transition-opacity">
                    {{ $track['album']['name'] ?? 'Álbum desconhecido' }}
                </span>
                </div>
                <div class="p-4 flex flex-col gap-1 flex-1">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $track['name'] }}">
                    {{ $track['name'] }}
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 truncate" title="{{ $artists }}">
                    {{ $artists }}
                </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif
</div>
@endsection

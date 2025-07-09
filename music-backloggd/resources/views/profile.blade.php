@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-8 md:p-12 bg-gray-900 rounded-3xl flex flex-col md:flex-row gap-10">

    <div class="w-full md:w-1/3 flex flex-col items-center md:items-start flex-shrink-0 mb-8 md:mb-0">
        <img
            src="{{ $user->avatar ?? $user->spotify_image ?? 'https://via.placeholder.com/150' }}"
            alt="Avatar do usuário"
            class="w-40 h-40 rounded-full border-4 border-green-500 shadow-xl object-cover transition-transform duration-200 hover:scale-105 mb-6"
        >
        <h1 class="text-3xl md:text-4xl font-bold text-green-400 mb-1">
            {{ $user->name ?? 'Usuário' }}
        </h1>
        <p class="text-gray-300 mb-2">
            {{ $user->email ?? 'Email não disponível' }}
        </p>
        <div class="flex flex-col gap-1 text-gray-400 text-sm">
            <span>Membro desde: <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span></span>
            <span>Total de músicas ouvidas: <span class="font-medium">{{ $totalTracks ?? 0 }}</span></span>
            <span>Total de playlists: <span class="font-medium">{{ $totalPlaylists ?? 0 }}</span></span>
            <span>Total de artistas seguidos: <span class="font-medium">{{ $totalArtists ?? 0 }}</span></span>
        </div>
    </div>

    <div class="w-full md:w-2/3 mt-10 md:mt-0">
        <h3 class="text-2xl font-bold mb-6 text-green-300">Minhas Avaliações</h3>

        @php
            $latestReviews = $user->reviews->sortByDesc('created_at')->take(6);
        @endphp

        @if($latestReviews->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($latestReviews as $review)
                    <a href="{{ route('track.show', ['id' => $review->track_id]) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-xl transition overflow-hidden group">
                        <img src="{{ $review->track_image }}" alt="{{ $review->track_name }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-200">
                        <div class="p-4">
                            <h4 class="font-semibold text-base truncate text-gray-900 dark:text-gray-100">{{ $review->track_name }}</h4>
                            <p class="text-xs text-gray-500 mt-1">Nota: <span class="font-bold text-green-500">{{ $review->rating }}/5</span></p>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($user->reviews->count() > 6)
                <div class="flex justify-center mt-6">
                    <a href="{{ route('backlog.index', ['user' => $user->id]) }}" class="text-green-400 hover:underline font-semibold">Veja mais</a>
                </div>
            @endif
        @else
            <p class="text-gray-400">Você ainda não avaliou nenhuma música.</p>
        @endif
    </div>
</div>
@endsection

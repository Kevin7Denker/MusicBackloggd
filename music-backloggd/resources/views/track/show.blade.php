@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <a href="{{ route('home') }}" class="text-green-600 dark:text-green-400 hover:underline flex items-center gap-1 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Voltar para Dashboard
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col md:flex-row gap-6 items-center">
        <img src="{{ $track['album']['images'][0]['url'] ?? 'https://via.placeholder.com/192' }}"
             alt="Capa do álbum"
             class="w-40 h-40 md:w-48 md:h-48 rounded-lg object-cover shadow-md">

        <div class="flex-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $track['name'] }}</h1>

            <p class="text-lg text-gray-700 dark:text-gray-300 mt-2">
                <span class="font-medium">Artistas:</span>
                @foreach ($track['artists'] as $i => $artist)
                    {{ $artist['name'] }}@if(!$loop->last), @endif
                @endforeach
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                <span class="font-medium">Álbum:</span> {{ $track['album']['name'] }}
            </p>

            <a href="{{ $track['external_urls']['spotify'] }}"
               target="_blank"
               rel="noopener"
               class="inline-block mt-4 px-4 py-2 bg-green-500 dark:bg-green-600 text-white rounded hover:bg-green-600 dark:hover:bg-green-700 transition">
                Abrir no Spotify
            </a>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4 dark:text-gray-100">Avaliações</h2>

        @auth
            @php
                $userReview = $reviews->firstWhere('user_id', auth()->id());
            @endphp
            @if ($userReview)
                <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 p-4 rounded mb-4">
                    <p class="font-semibold dark:text-gray-100">Sua avaliação:</p>
                    <div class="flex items-center gap-2 text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 inline" fill="{{ $i <= $userReview->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 20 20">
                                <polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.58,17.99 10,13.72 3.42,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/>
                            </svg>
                        @endfor
                        <span class="text-sm text-gray-700 dark:text-gray-300">({{ $userReview->rating }} / 5)</span>
                    </div>
                    <p class="text-sm mt-2 dark:text-gray-200">{{ $userReview->comment }}</p>
                    <a href="{{ route('review.form', ['id' => $track['id']]) }}" class="text-green-600 dark:text-green-400 hover:underline mt-2 inline-block">Editar avaliação</a>
                </div>
            @else
                <a href="{{ route('review.form', ['id' => $track['id']]) }}"
                   class="inline-block mt-2 mb-4 px-4 py-2 bg-green-500 dark:bg-green-600 text-white rounded hover:bg-green-600 dark:hover:bg-green-700 transition font-semibold shadow">
                    Avaliar esta música
                </a>
            @endif
        @endauth

        @forelse ($reviews as $review)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('user.profile', ['id' => $review->user->id]) }}"
   class="font-semibold text-green-600 dark:text-green-400 hover:underline">
    {{ $review->user->name }}
</a>
                    <div class="flex items-center gap-1 text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 inline" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 20 20">
                                <polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.58,17.99 10,13.72 3.42,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $review->rating }} / 5)</span>
                </div>
                <p class="mt-2 dark:text-gray-200">{{ $review->comment }}</p>

                <div class="mt-4">
    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Comentários:</h4>

    @foreach ($review->comments as $comment)
        <div class="border-l-4 border-green-500 pl-4 mb-2 text-sm text-gray-700 dark:text-gray-300">
            <p class="mb-1"><strong>{{ $comment->user->name }}:</strong></p>
            <p>{{ $comment->comment }}</p>
        </div>
    @endforeach

    @auth
        @if ($review->user_id !== auth()->id())
            <form action="{{ route('review.comment.store', ['id' => $review->id]) }}" method="POST" class="mt-3">
                @csrf
                <textarea name="comment" rows="2" required
                          placeholder="Comente esta avaliação..."
                          class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200"></textarea>
                <button type="submit"
                        class="mt-2 px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition">
                    Enviar comentário
                </button>
            </form>
        @endif
    @endauth
</div>
            </div>

        @empty
            <p class="text-gray-500 dark:text-gray-400">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
        @endforelse

    </div>
</div>
@endsection

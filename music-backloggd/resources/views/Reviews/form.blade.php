@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-8 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
    <h2 class="text-3xl font-extrabold text-green-700 dark:text-green-400 mb-6 flex items-center gap-3">
        <span class="">{{ $track['name'] }}</span>
    </h2>

    <form method="POST" action="{{ route('review.save', ['id' => $track['id']]) }}" class="space-y-7">
        @csrf

        <input type="hidden" name="track_name" value="{{ $track['name'] }}">
        <input type="hidden" name="track_image" value="{{ $track['album']['images'][0]['url'] ?? '' }}">

        <div class="flex items-center gap-4 mb-4">
            @if(!empty($track['album']['images'][0]['url']))
                <img src="{{ $track['album']['images'][0]['url'] }}" alt="Capa do álbum" class="w-16 h-16 rounded shadow-md object-cover">
            @endif
            <div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $track['name'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ collect($track['artists'])->pluck('name')->join(', ') }}
                </div>
            </div>
        </div>

        <div>
            <label for="rating" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Nota <span class="text-xs text-gray-500">(1–5 estrelas)</span>
            </label>
            <div class="flex flex-row-reverse justify-end gap-1">
                @for ($i = 5; $i >= 1; $i--)
                    <input
                        type="radio"
                        id="star{{ $i }}"
                        name="rating"
                        value="{{ $i }}"
                        class="hidden peer"
                        {{ old('rating', $review->rating ?? '') == $i ? 'checked' : '' }}
                        required
                    >
                    <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 dark:text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors">
                        &#9733;
                    </label>
                @endfor
            </div>
            @error('rating')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="comment" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Comentário <span class="text-xs text-gray-500">(opcional)</span>
            </label>
            <textarea
                id="comment"
                name="comment"
                rows="5"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500 focus:border-green-500 transition resize-none p-3"
                placeholder="Escreva seu comentário aqui..."
            >{{ old('comment', $review->comment ?? '') }}</textarea>
            @error('comment')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full py-3 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold rounded-lg shadow-lg
                   transition-colors focus:outline-none focus:ring-4 focus:ring-green-400"
        >
            Salvar Avaliação
        </button>
    </form>
</div>
@endsection

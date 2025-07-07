@extends('layouts.app')

@section('content')
<div x-data="carousel()" x-init="init()" class="max-w-6xl mx-auto p-4 relative select-none">
    <div
        class="overflow-hidden rounded-xl shadow-lg"
        @touchstart="startTouch($event)"
        @touchmove="moveTouch($event)"
        @touchend="endTouch()"
    >
        <div
            class="flex transition-transform duration-500 ease-in-out"
            :style="`transform: translateX(-${currentIndex * 100}%)`"
        >
            @foreach($recentTracks as $item)
                @php $track = $item['track']; @endphp
                <a href="{{ route('track.show', ['id' => $track['id']]) }}"
                   class="min-w-full bg-white dark:bg-gray-800 p-6 flex items-center gap-6"
                >
                    <img src="{{ $track['album']['images'][0]['url'] ?? 'https://via.placeholder.com/150' }}"
                         alt="Capa do álbum"
                         class="w-32 h-32 rounded-lg object-cover shadow-md flex-shrink-0">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 truncate">{{ $track['name'] }}</h3>
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-1 truncate">
                            {{ implode(', ', array_column($track['artists'], 'name')) }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Álbum: {{ $track['album']['name'] }}
                        </p>
                        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Ouvido em: {{ \Carbon\Carbon::parse($item['played_at'])->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Prev/Next buttons -->
    <button @click="prev()"
            class="absolute top-1/2 left-2 -translate-y-1/2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full p-2 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition focus:outline-none"
            aria-label="Anterior">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button @click="next()"
            class="absolute top-1/2 right-2 -translate-y-1/2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full p-2 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition focus:outline-none"
            aria-label="Próximo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Indicators -->
    <div class="flex justify-center mt-4 space-x-2">
        @foreach ($recentTracks as $index => $item)
            <button @click="goTo({{ $index }})"
                    :class="currentIndex === {{ $index }} ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'"
                    class="w-3 h-3 rounded-full transition focus:outline-none"
                    aria-label="Ir para slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    @if(count($recentTracks) === 0)
        <p class="text-center text-gray-500 dark:text-gray-400 py-8">
            Você ainda não ouviu músicas recentemente ou não conseguimos carregar.
        </p>
    @endif
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
function carousel() {
    return {
        currentIndex: 0,
        startX: 0,
        currentX: 0,
        dragging: false,
        init() {

            setInterval(() => this.next(), 5000);
        },
        prev() {
            this.currentIndex = this.currentIndex === 0 ? {{ count($recentTracks) - 1 }} : this.currentIndex - 1;
        },
        next() {
            this.currentIndex = this.currentIndex === {{ count($recentTracks) - 1 }} ? 0 : this.currentIndex + 1;
        },
        goTo(index) {
            this.currentIndex = index;
        },
        startTouch(event) {
            this.startX = event.touches[0].clientX;
            this.dragging = true;
        },
        moveTouch(event) {
            if (!this.dragging) return;
            this.currentX = event.touches[0].clientX;
        },
        endTouch() {
            if (!this.dragging) return;
            const deltaX = this.currentX - this.startX;
            if (deltaX > 50) this.prev();
            else if (deltaX < -50) this.next();
            this.dragging = false;
            this.startX = 0;
            this.currentX = 0;
        }
    }
}
</script>
@endsection

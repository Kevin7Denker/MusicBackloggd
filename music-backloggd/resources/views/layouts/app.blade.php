<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Music Backloggd</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-300">
    <header class="bg-white dark:bg-gray-800 shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items">
        <h1 class="text-2xl font-bold text-green-600 dark:text-green-400">
            Music Backloggd
        </h1>
        </a>
        <div class="flex items-center gap-4">
            <form action="{{ route('spotify.search') }}" method="GET" class="flex items-center gap-2">
    <input
        type="text"
        name="q"
        placeholder="Buscar no Spotify..."
        class="px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
        required
    >
    <button
        type="submit"
        class="px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 transition-colors"
    >
        Buscar
    </button>
</form>
            @auth
    <a href="{{ route('backlog.index') }}" title="Backlog"
       class="flex items-center gap-2 px-3 py-2 rounded-md bg-blue-100 dark:bg-blue-700 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-600 dark:text-blue-200 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <span class="hidden sm:inline">Backlog</span>
    </a>
    <a href="{{ route('profile.show') }}" title="Perfil"
       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-700 hover:bg-green-100 dark:hover:bg-green-800 text-gray-800 dark:text-gray-200 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span class="hidden sm:inline">Perfil</span>
    </a>


    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button class="flex items-center gap-2 px-3 py-2 rounded-md bg-red-100 dark:bg-red-700 hover:bg-red-200 dark:hover:bg-red-800 text-red-600 dark:text-red-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
            </svg>
            <span class="hidden sm:inline">Logout</span>
        </button>
    </form>
@endauth
        </div>
    </div>
</header>


    <main class="flex-1 py-6">
        @yield('content')
    </main>

    <footer class="bg-white dark:bg-gray-800 shadow mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} Music Backloggd. Todos os direitos reservados.
        </div>
    </footer>
    <script>
        // Theme toggle logic
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');

        function setIcons() {
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }

        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        setIcons();

        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
            setIcons();
        });
    </script>
</body>
</html>

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
            <h1 class="text-2xl font-bold text-green-600 dark:text-green-400">
                Music Backloggd
            </h1>
            <div class="flex items-center gap-4">

                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                            Logout
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
        }

        // On load, set theme from localStorage or system
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

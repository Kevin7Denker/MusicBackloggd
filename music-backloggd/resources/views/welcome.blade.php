@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-400 via-blue-500 to-purple-600">
    <div class="bg-white/90 dark:bg-gray-900/90 rounded-2xl shadow-2xl px-8 py-12 max-w-xl w-full flex flex-col items-center text-center">
        <img src="https://img.icons8.com/ios-filled/100/4ade80/musical-notes.png" alt="Music Icon" class="w-20 h-20 mb-6 drop-shadow-lg">
        <h1 class="text-4xl md:text-5xl font-extrabold text-green-600 dark:text-green-400 mb-4 drop-shadow-lg">
            Bem-vindo ao Music Backloggd!
        </h1>
        <p class="text-lg md:text-xl text-gray-700 dark:text-gray-200 mb-8">
            Descubra, acompanhe e organize suas músicas favoritas em um só lugar.
        </p>
        <a href="{{ url('login') }}"
           class="inline-block bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-purple-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 text-lg">
            Entrar com sua conta
        </a>
    </div>

</div>
@endsection

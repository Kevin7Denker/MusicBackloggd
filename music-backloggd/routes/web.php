<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SpotifyController;
use App\Http\Controllers\Auth\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/backlog', function () {
    return 'Página do backlog (em breve!)';
})->name('backlog.index')->middleware('auth');

Route::get('/track/{id}', [DashboardController::class, 'show'])
    ->name('track.show')
    ->middleware('auth');

Route::get('/login', [SpotifyController::class, 'redirectToProvider']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/callback', [SpotifyController::class, 'handleProviderCallback']);

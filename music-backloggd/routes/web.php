<?php

use App\Http\Controllers\BacklogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SpotifyController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\SearchController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.profile');
Route::get('/backlog', [BacklogController::class, 'index'])->name('backlog.index');
Route::middleware(['auth'])->get('/spotify/search', [SearchController::class, 'index'])->name('spotify.search');
Route::get('/login', [SpotifyController::class, 'redirectToProvider']);
Route::get('/callback', [SpotifyController::class, 'handleProviderCallback']);
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
Route::get('/spotify/redirect', [SpotifyController::class, 'redirectToProvider'])->name('spotify.redirect');
Route::post('/spotify/disconnect', [SpotifyController::class, 'disconnect'])->name('spotify.disconnect');
Route::post('/playlists/create', [App\Http\Controllers\PlaylistController::class, 'store'])->name('playlists.create');
Route::middleware(['auth'])->group(function () {
    Route::get('/track/{id}/review', [ReviewController::class, 'form'])->name('review.form');
    Route::post('/track/{id}/review', [ReviewController::class, 'save'])->name('review.save');
});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
Route::get('/track/{id}', [DashboardController::class, 'show'])
    ->name('track.show')
    ->middleware('auth');
Route::get('/home', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('home');
Route::post('/review/{id}/comment', [ReviewCommentController::class, 'store'])
    ->middleware('auth')
    ->name('review.comment.store');

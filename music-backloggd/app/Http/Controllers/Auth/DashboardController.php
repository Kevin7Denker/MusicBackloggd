<?php

namespace App\Http\Controllers\Auth;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (method_exists($user, 'needsSpotifyTokenRefresh') && $user->needsSpotifyTokenRefresh()) {
            app(SpotifyController::class)->refreshSpotifyToken($user);
        }

        $response = Http::withOptions(['verify' => false])
            ->withToken($user->spotify_token)
            ->get('https://api.spotify.com/v1/me/player/recently-played', [
                'limit' => 50,
            ]);
        if ($response->failed()) {
            \Log::error('Spotify API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->status() === 401) {
                return redirect('/login')->withErrors([
                    'error' => 'Sessão do Spotify expirou. Por favor, faça login novamente.'
                ]);
            }

            if ($response->status() === 403) {
                return redirect('/login')->withErrors([
                    'error' => 'Permissão insuficiente. Conceda o acesso a "recentemente ouvidas".'
                ]);
            }

            $recentTracks = collect();
        } else {
            $items = $response->json()['items'] ?? [];

            $uniqueTracks = [];
            $trackIds = [];

            foreach ($items as $item) {
                $track = $item['track'];
                if (!in_array($track['id'], $trackIds)) {
                    $trackIds[] = $track['id'];
                    $uniqueTracks[] = $item;
                }
            }

            $recentTracks = collect($uniqueTracks);
        }

        $topTracks = collect();

$topResponse = Http::withOptions(['verify' => false])
    ->withToken($user->spotify_token)
    ->get('https://api.spotify.com/v1/me/top/tracks', [
        'limit' => 20,
        'time_range' => 'medium_term',
]);

if ($topResponse->successful()) {
    $topTracks = collect($topResponse->json()['items'] ?? []);
}

       return view('dashboard', compact('user', 'recentTracks', 'topTracks'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if (method_exists($user, 'needsSpotifyTokenRefresh') && $user->needsSpotifyTokenRefresh()) {
            app(SpotifyController::class)->refreshSpotifyToken($user);
        }

        $response = Http::withOptions(['verify' => false])
            ->withToken($user->spotify_token)
            ->get("https://api.spotify.com/v1/tracks/{$id}");

        if ($response->failed()) {
            \Log::error('Spotify Track API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            abort(404, 'Não foi possível carregar os detalhes da música.');
        }

        $track = $response->json();

        $reviews = Review::where('track_id', $track['id'])->with('user')->get();
return view('track.show', compact('track', 'reviews'));

    }
}

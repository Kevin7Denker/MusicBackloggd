<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Mostra a dashboard com cards das músicas ouvidas recentemente
     */
    public function index()
    {
        $user = Auth::user();

        // Verifica se o token precisa ser atualizado
        if (method_exists($user, 'needsSpotifyTokenRefresh') && $user->needsSpotifyTokenRefresh()) {
            app(SpotifyController::class)->refreshSpotifyToken($user);
        }

        // Chama Spotify para pegar músicas ouvidas recentemente
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
                return redirect('/login')->withErrors(['error' => 'Sessão do Spotify expirou. Por favor, faça login novamente.']);
            }

            if ($response->status() === 403) {
                return redirect('/login')->withErrors(['error' => 'Permissão insuficiente. Conceda o acesso a "recentemente ouvidas".']);
            }

            $recentTracks = [];
        } else {
            $items = $response->json()['items'] ?? [];

            // FILTRA SOMENTE TRACKS ÚNICAS
            $uniqueTracks = [];
            $trackIds = [];

            foreach ($items as $item) {
                $track = $item['track'];
                if (!in_array($track['id'], $trackIds)) {
                    $trackIds[] = $track['id'];
                    $uniqueTracks[] = $item;
                }
            }

            $recentTracks = $uniqueTracks;
        }

        return view('dashboard', compact('user', 'recentTracks'));
    }

    /**
     * Mostra detalhes de uma faixa específica
     */
    public function show($id)
    {
        $user = Auth::user();

        // Verifica se o token precisa ser atualizado
        if (method_exists($user, 'needsSpotifyTokenRefresh') && $user->needsSpotifyTokenRefresh()) {
            app(SpotifyController::class)->refreshSpotifyToken($user);
        }

        // Busca dados detalhados da música pelo ID
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

        return view('track.show', compact('track', 'user'));
    }
}
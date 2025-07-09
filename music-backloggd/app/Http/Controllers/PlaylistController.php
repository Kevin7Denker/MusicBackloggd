<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class PlaylistController extends Controller
{
    public function store()
    {
        $user = Auth::user();

        if (!$user->spotify_token || !$user->spotify_id) {
            return redirect()->back()->with('error', 'Sua conta do Spotify não está conectada.');
        }

        $token = $user->spotify_token;

        $reviews = $user->reviews()->whereNotNull('track_id')->get();

        $playlistsResponse = Http::withToken($token)
            ->withOptions(['verify' => false])
            ->get("https://api.spotify.com/v1/users/{$user->spotify_id}/playlists");

        if (!$playlistsResponse->ok()) {
            return redirect()->back()->with('error', 'Erro ao acessar suas playlists.');
        }

        $playlist = collect($playlistsResponse->json('items'))->firstWhere('name', 'Minhas Avaliações');

        if (!$playlist) {

            $createResponse = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->post("https://api.spotify.com/v1/users/{$user->spotify_id}/playlists", [
                    'name' => 'Minhas Avaliações',
                    'description' => 'Playlist gerada com músicas avaliadas no Music Backloggd',
                    'public' => false,
                ]);

            if (!$createResponse->ok()) {
                return redirect()->back()->with('error', 'Erro ao criar a playlist.');
            }

            $playlist = $createResponse->json();
        }

        $playlistId = $playlist['id'];

        $tracksInPlaylist = [];
        $next = "https://api.spotify.com/v1/playlists/{$playlistId}/tracks";

        while ($next) {
            $response = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->get($next);

            if (!$response->ok()) break;

            $data = $response->json();
            $tracksInPlaylist = array_merge(
                $tracksInPlaylist,
                collect($data['items'])->pluck('track.id')->filter()->toArray()
            );
            $next = $data['next'];
        }

        $existingTrackIds = collect($tracksInPlaylist)->unique();

        $newTrackUris = $reviews
            ->filter(fn($review) => !$existingTrackIds->contains($review->track_id))
            ->pluck('track_id')
            ->map(fn($id) => "spotify:track:$id")
            ->values()
            ->toArray();

        if (empty($newTrackUris)) {
            return redirect()->back()->with('message', 'Todas as músicas avaliadas já estão na playlist.');
        }

        $addResponse = Http::withToken($token)
            ->withOptions(['verify' => false])
            ->post("https://api.spotify.com/v1/playlists/{$playlistId}/tracks", [
                'uris' => array_slice($newTrackUris, 0, 100),
            ]);

        if (!$addResponse->ok()) {
            return redirect()->back()->with('error', 'Erro ao adicionar faixas à playlist.');
        }

        return redirect()->back()->with('message', count($newTrackUris) . ' músicas adicionadas à playlist com sucesso!');
    }
}

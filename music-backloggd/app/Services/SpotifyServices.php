<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpotifyServices
{
    protected $token;

    public function __construct()
    {
        $this->token = auth()->user()->spotify_token;
    }

    public function getTrack($id)
    {
        $response = Http::withToken($this->token)
                 ->withOptions(['verify' => false])
                 ->get("https://api.spotify.com/v1/tracks/{$id}");

        if ($response->failed()) {
            throw new \Exception("Erro ao buscar música no Spotify");
        }

        return $response->json();
    }

    public function searchTracks(string $query, int $limit = 10)
{
    $response = Http::withToken($this->token)
        ->withOptions(['verify' => false])
        ->get('https://api.spotify.com/v1/search', [
            'q' => $query,
            'type' => 'track',
            'limit' => $limit,
        ]);

    if ($response->failed()) {
        throw new \Exception("Erro ao buscar músicas: " . $response->body());
    }

    return $response->json()['tracks']['items'];
}


}

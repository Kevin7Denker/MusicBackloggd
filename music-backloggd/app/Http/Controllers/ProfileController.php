<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function show()
    {

{
    $user = auth()->user();

    $spotifyToken = $user->spotify_token;

    $recentTracksResponse = Http::withToken($spotifyToken)->withOptions(['verify' => false])->get('https://api.spotify.com/v1/me/player/recently-played?limit=50');
    $totalTracks = $recentTracksResponse->successful() ? $recentTracksResponse->json()['items'] ?? [] : [];
    $totalTracksCount = count($totalTracks);

    $artistsResponse = Http::withToken($spotifyToken)->withOptions(['verify' => false])->get('https://api.spotify.com/v1/me/following?type=artist&limit=1');
    $totalArtists = 0;
    if ($artistsResponse->successful()) {
        $totalArtists = $artistsResponse->json()['artists']['total'] ?? 0;
    }

    $playlistsResponse = Http::withToken($spotifyToken)->withOptions(['verify' => false])->get('https://api.spotify.com/v1/me/playlists?limit=1');
    $totalPlaylists = 0;
    if ($playlistsResponse->successful()) {
        $totalPlaylists = $playlistsResponse->json()['total'] ?? 0;
    }

    return view('profile', [
        'user' => $user,
        'totalTracks' => $totalTracksCount,
        'totalArtists' => $totalArtists,
        'totalAlbums' => 0,
        'totalPlaylists' => $totalPlaylists,
        'totalFollowers' => $user->followers_count ?? 0,
        'totalFollowing' => $user->following_count ?? 0,
        'totalFollowedPlaylists' => $user->followed_playlists_count ?? 0,
    ]);
}
    }



}

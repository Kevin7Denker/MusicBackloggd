<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Services\SpotifyServices;
use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('spotify')
             ->scopes([
        'user-read-email',
        'playlist-read-private',
        'playlist-read-collaborative',
        'user-read-recently-played',
        'user-library-read',
        'user-follow-read',
        'user-read-private',
        'user-top-read',
        'playlist-modify-private',
        'playlist-modify-public',
    ])
            ->with(['show_dialog' => 'true'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $driver = Socialite::driver('spotify');
            $driver->setHttpClient(new Client(['verify' => false]));
            $spotifyUser = $driver->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Erro ao autenticar com Spotify: ' . $e->getMessage()]);
        }

        $user = User::where('spotify_id', $spotifyUser->id)->first();

        if (!$user && $spotifyUser->email) {
            $user = User::where('email', $spotifyUser->email)->first();

            if ($user) {
                $user->spotify_id = $spotifyUser->id;
            }
        }

        if (!$user) {
            $user = new User();
            $user->password = bcrypt(Str::random(16));
            $user->email = $spotifyUser->email;
        }

        $user->name = $spotifyUser->name ?? $spotifyUser->nickname ?? $user->name;
        $user->avatar = $spotifyUser->avatar;
        $user->spotify_token = $spotifyUser->token;
        $user->spotify_expires_in = now()->addSeconds($spotifyUser->expiresIn);
        $user->spotify_id = $spotifyUser->id;

        if ($spotifyUser->refreshToken) {
            $user->spotify_refresh_token = $spotifyUser->refreshToken;
        }

        $user->save();

        Auth::login($user);

        return redirect('/home');
    }

    public function refreshSpotifyToken(User $user)
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->spotify_refresh_token,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $user->spotify_token = $data['access_token'];
            $user->spotify_expires_in = now()->addSeconds($data['expires_in']);
            $user->save();

            return true;
        }

        return false;
    }

}
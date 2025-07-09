<?php

namespace App\Http\Controllers;
use App\Models\User;
class UserController extends Controller{
public function show($id)
{
    $user = User::with(['reviews'])->findOrFail($id);

    $totalTracks = $user->reviews()->count();
    $totalPlaylists = 5;
    $totalArtists = 10;

    return view('profile', compact('user', 'totalTracks', 'totalPlaylists', 'totalArtists'));
}
}

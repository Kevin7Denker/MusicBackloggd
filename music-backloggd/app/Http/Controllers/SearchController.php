<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpotifyServices;

class SearchController extends Controller
{
    public function index(Request $request, SpotifyServices $spotify)
    {
        $results = [];

        if ($request->has('q') && $request->filled('q')) {
            $results = $spotify->searchTracks($request->input('q'));
        }

        return view('spotify.search', compact('results'));
    }
}

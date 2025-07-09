<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\SpotifyServices;
class ReviewController extends Controller
{
     protected $spotify;

    public function __construct(SpotifyServices $spotify)
    {
        $this->spotify = $spotify;
    }
   public function form($id, SpotifyServices $spotify)
{
    $track = $spotify->getTrack($id);

    $review = Review::where('user_id', auth()->id())
                     ->where('track_id', $id)
                     ->first();

    return view('reviews.form', compact('track', 'review'));
}

public function save(Request $request, $id)
{
    $validated = $request->validate([
        'comment' => 'nullable|string|max:1000',
        'rating' => 'nullable|integer|min:1|max:5',
        'track_name' => 'required|string',
        'track_image' => 'nullable|string',
    ]);

    $review = Review::updateOrCreate(
        ['user_id' => auth()->id(), 'track_id' => $id],
        [
            'track_name' => $validated['track_name'],
            'track_image' => $validated['track_image'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]
    );

    return redirect()->route('track.show', ['id' => $id])->with('success', 'Avaliação salva!');
}
}
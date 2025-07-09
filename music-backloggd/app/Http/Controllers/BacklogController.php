<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class BacklogController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('track_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = (clone $query)->orderByDesc('created_at')->paginate(10);
        $averageRating = (clone $query)->avg('rating');

        $ratingDistribution = (clone $query)
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Ensure all ratings from 1 to 5 are present
        $allRatings = collect(range(1, 5))->mapWithKeys(function ($rating) use ($ratingDistribution) {
            return [$rating => $ratingDistribution->get($rating, 0)];
        });

        return view('backlog.index', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'ratingDistribution' => $allRatings,
        ]);
    }
}
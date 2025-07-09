<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewCommentController extends Controller
{
    public function store(Request $request, $reviewId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($reviewId);

        ReviewComment::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'comment' => $request->input('comment'),
        ]);

        return back()->with('success', 'Comentário enviado com sucesso.');
    }
}

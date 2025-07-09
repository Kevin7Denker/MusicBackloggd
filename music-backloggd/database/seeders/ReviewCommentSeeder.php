<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewCommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach (Review::all() as $review) {
            $commenters = $users->where('id', '!=', $review->user_id)->random(2); // 2 usuários diferentes

            foreach ($commenters as $user) {
                ReviewComment::create([
                    'review_id' => $review->id,
                    'user_id' => $user->id,
                    'comment' => fake()->sentence(),
                ]);
            }
        }
    }
}

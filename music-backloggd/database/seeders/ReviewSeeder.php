<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $trackIds = [
            [
                'id' => '0VjIjW4GlUZAMYd2vXMi3b', // Blinding Lights
                'name' => 'Blinding Lights',
                'image' => 'https://i.scdn.co/image/ab67616d0000b2738863bc11d2aa12b54f5aeb36'
            ],
            [
                'id' => '7qiZfU4dY1lWllzX7mPBI3', // Shape of You
                'name' => 'Shape of You',
                'image' => 'https://i.scdn.co/image/ab67616d0000b273ba5db46f4b838ef6027e6f96'
            ],
            [
                'id' => '1WR5CWBPmFHhl6WVAwJbTt', // Die with a Smile (Lady Gaga & Bruno Mars)
                'name' => 'Die with a Smile',
                'image' => 'https://i.scdn.co/image/ab67616d0000b273...'
            ],
            [
                'id' => '2M5XueK1vu5XLizbmQebJY', // APT. by ROSÉ & Bruno Mars
                'name' => 'APT.',
                'image' => 'https://i.scdn.co/image/ab67616d0000b273...'
            ],
            [
                'id' => '5G9M0O8FqxYUeIz5Sfk2N9', // DTMF – Bad Bunny
                'name' => 'DTMF',
                'image' => 'https://i.scdn.co/image/ab67616d0000b273...'
            ],
        ];

        foreach (User::all() as $user) {
            foreach ($trackIds as $track) {
                Review::create([
                    'user_id' => $user->id,
                    'track_id' => $track['id'],
                    'track_name' => $track['name'],
                    'track_image' => $track['image'],
                    'comment' => fake()->sentence(),
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}

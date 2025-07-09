<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model{
protected $fillable = [
'user_id',
'track_id',
'track_name',
'track_image',
'comment',
'rating',
];

public function user()
{
return $this->belongsTo(User::class);
}

public function comments()
{
    return $this->hasMany(ReviewComment::class);
}
}

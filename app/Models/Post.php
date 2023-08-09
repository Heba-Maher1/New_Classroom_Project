<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id' , 'user_id' , 'content'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
        ]);
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
}

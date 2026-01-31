<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Idea;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['idea_id', 'user_id', 'is_upvote'];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
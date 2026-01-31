<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id', 'category_id']; 

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getScoreAttribute()
    {
        return $this->votes()->where('is_upvote', true)->count() - 
               $this->votes()->where('is_upvote', false)->count();
    }
}
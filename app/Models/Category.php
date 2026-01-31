<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // یک دسته‌بندی شامل چندین ایده است
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
}
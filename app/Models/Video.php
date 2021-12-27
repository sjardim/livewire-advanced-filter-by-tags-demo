<?php

namespace App\Models;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

     /**
     * Get all of the article's tags.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

<?php

namespace App\Models;

use App\Models\Video;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }


    public function videos()
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }

}

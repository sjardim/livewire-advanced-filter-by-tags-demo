<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {       
        $articles = Article::factory()->count(500)->create();

        $videos = Video::factory()->count(500)->create();

        $tags = Tag::factory()->count(50)->create();

        foreach ((range(1,500)) as $index) {
            Article::find(rand(1,500))->tags()->sync([
                rand(1, (rand(1,25))), 
                rand(1, (rand(26,50))),
            ]);
            Video::find(rand(1,500))->tags()->sync([
                rand(1, (rand(1,25))), 
                rand(1, (rand(26,50))),
            ]);            
        };
    }
}

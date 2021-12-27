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
        $articles = Article::factory()->count(100)->create();

        $videos = Video::factory()->count(100)->create();

        $tags = Tag::factory()->count(50)->create();

        foreach ((range(1,50)) as $index) {
            Article::find(rand(1,100))->tags()->sync([
                rand(1, (rand(1,10))), 
                rand(1, (rand(11,20))), 
                rand(1, (rand(21,30))), 
                rand(1, (rand(31,40))), 
                rand(1, (rand(41,50))), 
            ]);
            Video::find(rand(1,100))->tags()->sync([
                rand(1, (rand(1,10))), 
                rand(1, (rand(11,20))), 
                rand(1, (rand(21,30))), 
                rand(1, (rand(31,40))), 
                rand(1, (rand(41,50))), 
            ]);            
        };
    }
}

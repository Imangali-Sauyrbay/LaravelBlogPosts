<?php

namespace Database\Seeders;

use App\Models\Blogpost;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogpostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagsCount = Tag::count();

        if($tagsCount <= 0) {
            $this->command->error('No Tags Found! Scipping Assigning Tags To Blogpost!');
            return;
        }

        $min = max((int) $this->command->ask('Minimum Tags For The Blogpost?', 1), 0);
        $max = min((int) $this->command->ask('Maximum Tags For The Blogpost?', $tagsCount), $tagsCount);

        Blogpost::all()->each(function(Blogpost $post) use($min, $max) {
            $takeCount = random_int($min, $max);
            $tags = Tag::inRandomOrder()->take($takeCount)->get()->pluck('id');
            $post->tags()->sync($tags);
        });
    }
}

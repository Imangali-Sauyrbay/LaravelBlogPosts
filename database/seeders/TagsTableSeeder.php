<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\CommonMark\Normalizer\SlugNormalizer;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = collect([
            'Sport',
            'Economy',
            'Politics',
            'Science',
            'Entertainment',
            'Fun',
            'Tech',
            'Blog'
        ]);

        $slugNormalizer = new SlugNormalizer;

        $tags->each(function($name) use ($slugNormalizer) {
            $tag = new Tag();
            $tag->name = $name;
            $tag->slug = $slugNormalizer->normalize($name);
            $tag->save();
        });
    }
}

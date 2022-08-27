<?php

namespace Database\Factories;

use App\Models\Blogpost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blogpost>
 */
class BlogpostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $result = array();

        $result['title'] = fake()->sentence(rand(6, 20));
        $result['content'] = fake()->paragraphs(rand(1, 15), true);
        $result['slug'] = Blogpost::getSlug($result['title']);

        return $result;
    }

    public function withFirstAuthor()
    {
        return $this->state(function (array $attributes) {
            $attributes['author_id'] = 1;
            return $attributes;
        });
    }


}

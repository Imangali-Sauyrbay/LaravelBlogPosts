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
        return [
            'title' => fake()->sentence(rand(6, 20)),
            'content' => fake()->paragraphs(rand(1, 15), true),
            'created_at' => fake()->dateTimeBetween('-6 month')
        ];
    }

    public function withFirstAuthor()
    {
        return $this->state(function (array $attributes) {
            $attributes['author_id'] = 1;
            return $attributes;
        });
    }


}

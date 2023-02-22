<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'slug' => \Str::of($title)->slug('-'),
            'title' => $title,
            'content' => $this->faker->text(200),
            'status' => 'draft'
        ];
    }
}

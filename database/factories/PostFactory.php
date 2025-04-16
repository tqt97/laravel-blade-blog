<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'title' => $this->faker->sentence,
            // 'slug' => null,
            'excerpt' => $this->faker->text(100),
            'content' => $this->faker->paragraphs(3, true),
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}

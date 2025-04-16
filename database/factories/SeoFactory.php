<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seo>
 */
class SeoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'metaable_id' => 1,
            'metaable_type' => 'App\\Models\\Post',
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->text(200),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }
}

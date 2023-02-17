<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' =>
                User::exists() && fake()->boolean
                    ? User::inRandomOrder()->value('id')
                    : User::factory()->create()->id,
            'title' => fake()->realTextBetween(16, 64),
            'body' => fake()->realTextBetween(256, 1024),
            'is_public' => fake()->boolean
        ];
    }
}

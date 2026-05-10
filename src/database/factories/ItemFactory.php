<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => 1000,
            'condition' => 1,
            'image' => 'test.jpg',
        ];
    }
}

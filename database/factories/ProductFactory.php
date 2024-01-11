<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'delivery_id' => Delivery::inRandomOrder()->first()?->id ?? Delivery::factory(),
            'name' => fake()->name(),
            'weight' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }
}

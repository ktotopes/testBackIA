<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'to_address' => fake()->address(),
            'from_address' => fake()->address(),
            'from_coordinates' => implode(', ', fake()->localCoordinates()),
            'to_coordinates' => implode(', ', fake()->localCoordinates()),
            'distance' => fake()->numberBetween(1,100),
            'price' => fake()->numberBetween(1,100),
            'weights' => fake()->numberBetween(1,100),
            'sender' => fake()->name(),
            'sender_contact' => fake()->phoneNumber(),
            'recipient' => fake()->name(),
            'recipient_contact' => fake()->phoneNumber(),
            'should_delivered' => now()->addDays(10),
        ];
    }
}

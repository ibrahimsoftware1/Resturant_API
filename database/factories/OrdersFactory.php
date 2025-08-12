<?php

namespace Database\Factories;

use App\Models\Tables;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => Tables::factory(),
            'user_id' => User::factory(), // waiter
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Tables;
use Illuminate\Database\Eloquent\Factories\Factory;

class TablesFactory extends Factory
{
    protected $model = Tables::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'status' => fake()->randomElement(['reserved', 'occupied', 'available']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

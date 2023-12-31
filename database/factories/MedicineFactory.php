<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => fake()->name(),
            "quantityInStock" => fake()->numberBetween(0, 100),
            "price" => fake()->numberBetween(0, 10000),
            "prescriptionRequired" => fake()->boolean(),
            "expireDate" => fake()->date()
        ];
    }
}

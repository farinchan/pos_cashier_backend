<?php

namespace Database\Factories;

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
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(1000, 100000),
            'stock' => $this->faker->randomNumber(2),
            'category_id' => $this->faker->numberBetween(1, 9),
            // 'image' => $this->faker->imageUrl(),
            'barcode' => $this->faker->ean13(),
        ];
    }
}

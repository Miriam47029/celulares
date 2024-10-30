<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Celulares>
 */
class CelularesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modelo' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'precio' => $this->faker->randomFloat(2, 1000, 5000),
            'marca_id' => $this->faker->numberBetween(1,15),
            'camara' => $this->faker->randomFloat(1, 5, 108),
            'foto' => $this->faker->imageUrl(640, 480, 'tech', true),  // URL de imagen falsa
        ];
    }
}

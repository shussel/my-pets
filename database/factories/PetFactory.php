<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\SexEnum;
use App\Enums\SpeciesEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $species = fake()->randomElement(SpeciesEnum::values());

        return [
            'user_id' => '0',
            'name' => fake()->petname(),
            'species' => $species,
            'sex' => fake()->randomElement(SexEnum::values()),
            'weight' => fake()->petweight($species),
            'birth_date' => fake()->dateTimeBetween('-20 years', '-1 day'),
        ];
    }
}

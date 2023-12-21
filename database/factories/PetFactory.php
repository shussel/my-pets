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
        return [
            'user_id' => '0',
            'name' => fake()->petname(),
            'species' => fake()->randomElement(SpeciesEnum::values()),
            'sex' => fake()->randomElement(SexEnum::values()),
            'birth_date' => fake()->dateTimeBetween('-20 years', '-6 weeks'),
        ];
    }
}

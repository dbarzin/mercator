<?php

namespace Database\Factories;

use App\Models\Information;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Information>
 */
class InformationFactory extends Factory
{
    protected $model = Information::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentences(5, true),
            'owner' => $this->faker->word(),
            'administrator' => $this->faker->word(),
            'storage' => $this->faker->word(),
            'security_need_c' => $this->faker->numberBetween(0, 4),
            'security_need_i' => $this->faker->numberBetween(0, 4),
            'security_need_a' => $this->faker->numberBetween(0, 4),
            'security_need_t' => $this->faker->numberBetween(0, 4),
            'security_need_auth' => $this->faker->numberBetween(0, 4),
            'sensitivity' => $this->faker->word(),
            'constraints' => $this->faker->word(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\MacroProcessus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MacroProcessus>
 */
class MacroProcessusFactory extends Factory
{
    protected $model = MacroProcessus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(10, true),
            'description' => fake()->optional()->sentence(20),
            'io_elements' => fake()->optional()->text(200),
            'security_need_c' => fake()->optional()->numberBetween(0, 4),
            'security_need_i' => fake()->optional()->numberBetween(0, 4),
            'security_need_a' => fake()->optional()->numberBetween(0, 4),
            'security_need_t' => fake()->optional()->numberBetween(0, 4),
            'security_need_auth' => fake()->optional()->numberBetween(0, 4),
            'owner' => fake()->optional()->name(),
        ];
    }
}

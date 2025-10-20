<?php

namespace Database\Factories;

use App\Models\MacroProcessus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MacroProcessus>
 */
class MacroProcessFactory extends Factory
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

    /**
     * MacroProcessus avec des besoins de sécurité élevés
     */
    public function highSecurity(): static
    {
        return $this->state(fn (array $attributes) => [
            'security_need_c' => fake()->numberBetween(3, 4),
            'security_need_i' => fake()->numberBetween(3, 4),
            'security_need_a' => fake()->numberBetween(3, 4),
            'security_need_t' => fake()->numberBetween(3, 4),
            'security_need_auth' => fake()->numberBetween(3, 4),
        ]);
    }

    /**
     * MacroProcessus avec des besoins de sécurité faibles
     */
    public function lowSecurity(): static
    {
        return $this->state(fn (array $attributes) => [
            'security_need_c' => fake()->numberBetween(0, 1),
            'security_need_i' => fake()->numberBetween(0, 1),
            'security_need_a' => fake()->numberBetween(0, 1),
            'security_need_t' => fake()->numberBetween(0, 1),
            'security_need_auth' => fake()->numberBetween(0, 1),
        ]);
    }
}

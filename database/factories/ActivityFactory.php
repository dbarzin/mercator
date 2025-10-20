<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(10, true),
            'description' => fake()->optional()->sentence(),
            'recovery_time_objective' => fake()->optional()->numberBetween(0, 10080),
            'recovery_point_objective' => fake()->optional()->numberBetween(0, 10080),
            'maximum_tolerable_downtime' => fake()->optional()->numberBetween(0, 20160),
            'maximum_tolerable_data_loss' => fake()->optional()->numberBetween(0, 20160),
        ];
    }
}
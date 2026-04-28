<?php

namespace App\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
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
            'name' => $this->faker->words(10, true),
            'description' => $this->faker->optional()->sentence(),
            'recovery_time_objective' => $this->faker->optional()->numberBetween(0, 10080),
            'recovery_point_objective' => $this->faker->optional()->numberBetween(0, 10080),
            'maximum_tolerable_downtime' => $this->faker->optional()->numberBetween(0, 20160),
            'maximum_tolerable_data_loss' => $this->faker->optional()->numberBetween(0, 20160),
        ];
    }
}

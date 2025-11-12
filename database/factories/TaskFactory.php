<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the default attributes for a Task model factory.
     *
     * Provides an associative array with:
     * - `name`: a fake full name,
     * - `description`: fake text,
     * - `created_at` and `updated_at`: Carbon timestamps set to now.
     *
     * @return array<string, mixed> Associative array of attribute names to values.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

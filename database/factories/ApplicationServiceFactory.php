<?php

namespace Database\Factories;

use App\Models\ApplicationService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationServiceFactory extends Factory
{
    protected $model = ApplicationService::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'exposition' => $this->faker->word(),
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

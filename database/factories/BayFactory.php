<?php

namespace Database\Factories;

use App\Models\Bay;
use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BayFactory extends Factory
{
    protected $model = Bay::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'room_id' => Building::factory(),
        ];
    }
}

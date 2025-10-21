<?php

namespace Database\Factories;

use App\Models\ForestAd;
use App\Models\ZoneAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ForestAdFactory extends Factory
{
    protected $model = ForestAd::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'zone_admin_id' => ZoneAdmin::factory(),
        ];
    }
}

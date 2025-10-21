<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Site;
use App\Models\WifiTerminal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WifiTerminalFactory extends Factory
{
    protected $model = WifiTerminal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'description' => $this->faker->text(),
            'vendor' => $this->faker->word(),
            'product' => $this->faker->word(),
            'version' => $this->faker->word(),
            'address_ip' => $this->faker->ipv4(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'site_id' => Site::factory(),
            'building_id' => Building::factory(),
        ];
    }
}

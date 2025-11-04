<?php

namespace Database\Factories;

use App\Models\Bay;
use App\Models\Building;
use App\Models\Entity;
use App\Models\Peripheral;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PeripheralFactory extends Factory
{
    protected $model = Peripheral::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'icon_id' => null,
            'description' => $this->faker->text(),
            'vendor' => $this->faker->word(),
            'product' => $this->faker->word(),
            'version' => $this->faker->word(),
            'responsible' => $this->faker->word(),
            'address_ip' => $this->faker->ipv4(),
            'domain' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'site_id' => Site::factory(),
            'building_id' => Building::factory(),
            'bay_id' => Bay::factory(),
            'provider_id' => Entity::factory(),
        ];
    }
}

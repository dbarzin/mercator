<?php

namespace Database\Factories;

use App\Models\Bay;
use App\Models\Building;
use App\Models\PhysicalServer;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhysicalServerFactory extends Factory
{
    protected $model = PhysicalServer::class;

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
            'configuration' => $this->faker->word(),
            'physical_switch_id' => null,
            'address_ip' => $this->faker->ipv4(),
            'cpu' => $this->faker->word(),
            'memory' => $this->faker->word(),
            'disk' => $this->faker->word(),
            'disk_used' => $this->faker->word(),
            'operating_system' => $this->faker->word(),
            'install_date' => Carbon::now(),
            'update_date' => Carbon::now(),
            'patching_group' => $this->faker->word(),
            'paching_frequency' => $this->faker->randomNumber(),
            'next_update' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'site_id' => Site::factory(),
            'building_id' => Building::factory(),
            'bay_id' => Bay::factory(),
        ];
    }
}

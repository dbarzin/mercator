<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Phone;
use App\Models\PhysicalSwitch;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhoneFactory extends Factory
{
    protected $model = Phone::class;

    /**
     * Define default attribute values for creating Phone model instances.
     *
     * The returned array maps Phone model attributes to generated values:
     * - `name`, `type`, `description`, `vendor`, `product`, `version`, `address_ip` are populated with faker data.
     * - `physical_switch_id`, `site_id`, `building_id` are factory definitions that create the related models.
     * - `created_at` and `updated_at` are set to the current timestamp.
     *
     * @return array<string,mixed> Associative array of Phone attributes keyed by column name.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'description' => $this->faker->text(),
            'vendor' => $this->faker->word(),
            'product' => $this->faker->word(),
            'version' => $this->faker->word(),
            'physical_switch_id' => PhysicalSwitch::factory(),
            'address_ip' => $this->faker->ipv4(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'site_id' => Site::factory(),
            'building_id' => Building::factory(),
        ];
    }
}
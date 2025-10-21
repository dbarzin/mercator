<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\ExternalConnectedEntity;
use App\Models\Network;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExternalConnectedEntityFactory extends Factory
{
    protected $model = ExternalConnectedEntity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'security' => $this->faker->word(),
            'type' => $this->faker->word(),
            'src' => $this->faker->word(),
            'src_desc' => $this->faker->word(),
            'dest' => $this->faker->word(),
            'dest_desc' => $this->faker->word(),
            'contacts' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'entity_id' => Entity::factory(),
            'network_id' => Network::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\LogicalServer;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogicalServerFactory extends Factory
{
    protected $model = LogicalServer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'active' => $this->faker->boolean,
            'operating_system' => $this->faker->word,
            'environment' => $this->faker->word,
            'type' => $this->faker->word,
            'attributes' => json_encode(['key' => $this->faker->word]),
            'configuration' => json_encode(['setting' => $this->faker->word]),
            'address_ip' => $this->faker->ipv4,
        ];
    }
}

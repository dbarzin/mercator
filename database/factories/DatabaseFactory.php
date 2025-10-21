<?php

namespace Database\Factories;

use App\Models\Database;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DatabaseFactory extends Factory
{
    protected $model = Database::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'description' => $this->faker->text(),
            'responsible' => $this->faker->word(),
            'external' => $this->faker->word(),
            'security_need_c' => $this->faker->randomNumber(),
            'security_need_i' => $this->faker->randomNumber(),
            'security_need_a' => $this->faker->randomNumber(),
            'security_need_t' => $this->faker->randomNumber(),
            'security_need_auth' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'entity_resp_id' => Entity::factory(),
        ];
    }
}

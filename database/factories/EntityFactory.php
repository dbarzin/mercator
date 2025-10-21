<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EntityFactory extends Factory
{
    protected $model = Entity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'icon_id' => $this->faker->randomNumber(),
            'security_level' => $this->faker->word(),
            'contact_point' => $this->faker->word(),
            'description' => $this->faker->text(),
            'is_external' => $this->faker->boolean(),
            'entity_type' => $this->faker->word(),
            'attributes' => $this->faker->word(),
            'reference' => $this->faker->word(),
            'external_ref_id' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

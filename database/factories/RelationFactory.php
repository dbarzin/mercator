<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\Relation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RelationFactory extends Factory
{
    protected $model = Relation::class;

    public function definition(): array
    {
        return [
            'importance' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'description' => $this->faker->text(),
            'attributes' => $this->faker->word(),
            'reference' => $this->faker->word(),
            'responsible' => $this->faker->word(),
            'order_number' => $this->faker->word(),
            'active' => $this->faker->boolean(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
            'comments' => $this->faker->word(),
            'security_need_c' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_i' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_a' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_t' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_auth' => $this->faker->optional()->numberBetween(0, 4),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'source_id' => Entity::factory(),
            'destination_id' => Entity::factory(),
        ];
    }
}

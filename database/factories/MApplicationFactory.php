<?php

namespace Database\Factories;

use App\Models\ApplicationBlock;
use App\Models\Entity;
use App\Models\MApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MApplicationFactory extends Factory
{
    protected $model = MApplication::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'vendor' => $this->faker->word(),
            'product' => $this->faker->word(),
            'security_need_c' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_i' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_a' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_t' => $this->faker->optional()->numberBetween(0, 4),
            'security_need_auth' => $this->faker->randomNumber(),
            'responsible' => $this->faker->word(),
            'functional_referent' => $this->faker->word(),
            'type' => $this->faker->word(),
            'icon_id' => $this->faker->randomNumber(),
            'technology' => $this->faker->word(),
            'external' => $this->faker->word(),
            'users' => $this->faker->word(),
            'editor' => $this->faker->word(),
            'documentation' => $this->faker->word(),
            'version' => $this->faker->word(),
            'rto' => $this->faker->randomNumber(),
            'rpo' => $this->faker->randomNumber(),
            'install_date' => Carbon::now(),
            'update_date' => Carbon::now(),
            'attributes' => $this->faker->word(),
            'patching_frequency' => $this->faker->randomNumber(),
            'next_update' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'entity_resp_id' => Entity::factory(),
            'application_block_id' => ApplicationBlock::factory(),
        ];
    }
}

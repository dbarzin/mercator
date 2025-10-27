<?php

namespace Database\Factories;

use App\Models\Flux;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FluxFactory extends Factory
{
    protected $model = Flux::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'crypted' => $this->faker->boolean(),
            'bidirectional' => $this->faker->boolean(),
            'nature' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'attributes' => $this->faker->words(3, true),

            'application_source_id' => null,
            'service_source_id' => null,
            'module_source_id' => null,
            'database_source_id' => null,
            'application_dest_id' => null,
            'service_dest_id' => null,
            'module_dest_id' => null,
            'database_dest_id' => null,
        ];
    }
}

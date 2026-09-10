<?php

namespace App\Factories;

use App\Models\ApplicationFlow;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationFlowFactory extends Factory
{
    protected $model = ApplicationFlow::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'crypted' => $this->faker->boolean(),
            'bidirectional' => $this->faker->boolean(),
            'type' => $this->faker->word(),
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

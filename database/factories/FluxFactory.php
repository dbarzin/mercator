<?php

namespace Database\Factories;

use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\Flux;
use App\Models\MApplication;
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
            'attributes' => $this->faker->word(3),

            'application_source_id' => MApplication::factory(),
            'service_source_id' => ApplicationService::factory(),
            'module_source_id' => ApplicationModule::factory(),
            'database_source_id' => Database::factory(),
            'application_dest_id' => MApplication::factory(),
            'service_dest_id' => ApplicationService::factory(),
            'module_dest_id' => ApplicationModule::factory(),
            'database_dest_id' => Database::factory(),
        ];
    }
}

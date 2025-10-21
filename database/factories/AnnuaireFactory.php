<?php

namespace Database\Factories;

use App\Models\Annuaire;
use App\Models\ZoneAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AnnuaireFactory extends Factory
{
    protected $model = Annuaire::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'solution' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'zone_admin_id' => ZoneAdmin::factory(),
        ];
    }
}

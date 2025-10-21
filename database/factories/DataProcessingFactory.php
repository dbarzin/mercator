<?php

namespace Database\Factories;

use App\Models\DataProcessing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DataProcessingFactory extends Factory
{
    protected $model = DataProcessing::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'legal_basis' => $this->faker->word(),
            'description' => $this->faker->text(),
            'responsible' => $this->faker->word(),
            'purpose' => $this->faker->word(),
            'categories' => $this->faker->word(),
            'recipients' => $this->faker->word(),
            'transfert' => $this->faker->word(),
            'retention' => $this->faker->word(),
            'controls' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

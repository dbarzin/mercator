<?php

namespace Database\Factories;

use App\Models\RelationValue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RelationValueFactory extends Factory
{
    protected $model = RelationValue::class;

    public function definition(): array
    {
        return [
            'date_price' => Carbon::now(),
            'price' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

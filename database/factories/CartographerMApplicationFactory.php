<?php

namespace Database\Factories;

use App\Models\CartographerMApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CartographerMApplicationFactory extends Factory
{
    protected $model = CartographerMApplication::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'm_application_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

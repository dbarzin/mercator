<?php

namespace Database\Factories;

use App\Models\AdminUser;
use App\Models\DomaineAd;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AdminUserFactory extends Factory
{
    protected $model = AdminUser::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->word(),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'type' => $this->faker->word(),
            'attributes' => $this->faker->word(),
            'icon_id' => $this->faker->randomNumber(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'domain_id' => DomaineAd::factory(),
        ];
    }
}

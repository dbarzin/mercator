<?php

namespace Database\Factories;

use App\Models\DomaineAd;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DomaineAdFactory extends Factory
{
    protected $model = DomaineAd::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'domain_ctrl_cnt' => $this->faker->randomNumber(),
            'user_count' => $this->faker->randomNumber(),
            'machine_count' => $this->faker->randomNumber(),
            'relation_inter_domaine' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

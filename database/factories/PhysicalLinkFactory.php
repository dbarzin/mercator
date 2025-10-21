<?php

namespace Database\Factories;

use App\Models\PhysicalLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhysicalLinkFactory extends Factory
{
    protected $model = PhysicalLink::class;

    public function definition(): array
    {
        return [
            'src_port' => $this->faker->word(),
            'dest_port' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CPEProduct;
use App\Models\CPEVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CPEVersionFactory extends Factory
{
    protected $model = CPEVersion::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),

            'cpe_product_id' => CPEProduct::factory(),
        ];
    }
}

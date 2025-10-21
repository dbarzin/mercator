<?php

namespace Database\Factories;

use App\Models\CPEProduct;
use App\Models\CPEVendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CPEProductFactory extends Factory
{
    protected $model = CPEProduct::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),

            'cpe_vendor_id' => CPEVendor::factory(),
        ];
    }
}

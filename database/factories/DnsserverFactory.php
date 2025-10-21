<?php

namespace Database\Factories;

use App\Models\Dnsserver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DnsserverFactory extends Factory
{
    protected $model = Dnsserver::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'address_ip' => $this->faker->ipv4(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

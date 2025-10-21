<?php

namespace Database\Factories;

use App\Models\Gateway;
use App\Models\Network;
use App\Models\Subnetwork;
use App\Models\Vlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubnetworkFactory extends Factory
{
    protected $model = Subnetwork::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'address' => $this->faker->address(),
            'ip_allocation_type' => $this->faker->ipv4(),
            'responsible_exp' => $this->faker->word(),
            'dmz' => $this->faker->word(),
            'wifi' => $this->faker->word(),
            'zone' => $this->faker->word(),
            'default_gateway' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'gateway_id' => Gateway::factory(),
            'vlan_id' => Vlan::factory(),
            'network_id' => Network::factory(),
        ];
    }
}

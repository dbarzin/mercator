<?php

namespace Database\Factories;

use App\Models\LogicalFlow;
use App\Models\LogicalServer;
use App\Models\Peripheral;
use App\Models\PhysicalSecurityDevice;
use App\Models\PhysicalServer;
use App\Models\Router;
use App\Models\StorageDevice;
use App\Models\Workstation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LogicalFlowFactory extends Factory
{
    protected $model = LogicalFlow::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'source_ip_range' => $this->faker->ipv4(),
            'dest_ip_range' => $this->faker->ipv4(),
            'source_port' => $this->faker->word(),
            'dest_port' => $this->faker->word(),
            'protocol' => $this->faker->word(),
            'description' => $this->faker->text(),
            'priority' => $this->faker->randomNumber(),
            'action' => $this->faker->word(),
            'users' => $this->faker->word(),
            'interface' => $this->faker->word(),
            'schedule' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'router_id' => Router::factory(),
            'logical_server_source_id' => LogicalServer::factory(),
            'peripheral_source_id' => Peripheral::factory(),
            'physical_server_source_id' => PhysicalServer::factory(),
            'storage_device_source_id' => StorageDevice::factory(),
            'workstation_source_id' => Workstation::factory(),
            'physical_security_device_source_id' => PhysicalSecurityDevice::factory(),
            'logical_server_dest_id' => LogicalServer::factory(),
            'peripheral_dest_id' => Peripheral::factory(),
            'physical_server_dest_id' => PhysicalServer::factory(),
            'storage_device_dest_id' => StorageDevice::factory(),
            'workstation_dest_id' => Workstation::factory(),
            'physical_security_device_dest_id' => PhysicalSecurityDevice::factory(),
        ];
    }
}

<?php

namespace App\Factories;

use App\Models\LogicalServer;
use App\Models\StorageDevice;
use App\Models\Backup;

use Illuminate\Database\Eloquent\Factories\Factory;

class BackupFactory extends Factory
{
    protected $model = Backup::class;

    public function definition(): array
    {
        return [
            'logical_server_id' => LogicalServer::factory(),
            'storage_device_id' => StorageDevice::factory(),
            'backup_frequency' => $this->faker->randomElement([1, 2, 3]),  // 1=daily, 2=weekly, 3=monthly
            'backup_cycle' => $this->faker->numberBetween(1, 5),
            'backup_retention' => $this->faker->randomElement([7, 14, 30, 60, 90, 180, 365]),
        ];
    }
}
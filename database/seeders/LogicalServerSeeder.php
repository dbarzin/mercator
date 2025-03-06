<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\LogicalServer;

// Logical Server Seeder
// Usage :
// php artisan db:seed --class=LogicalServerSeeder

class LogicalServerSeeder extends Seeder
{
    public function run()
    {
        LogicalServer::factory()->count(1500)->create();
    }
}

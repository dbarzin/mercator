<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Mercator\Core\License\LicenseService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        Commands\Cleanup::class,
        Commands\CertificateExpiration::class,
        Commands\CVESearch::class,
        Commands\CPEImport::class,
        Commands\MercatorModuleInstall::class,
        Commands\MercatorModuleEnable::class,
        Commands\MercatorModuleDisable::class,
        Commands\MercatorModuleUninstall::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('mercator:cleanup')->daily();
        $schedule->command('mercator:certificate-expiracy')->daily();
        $schedule->command('mercator:cve-search')->daily();
        $schedule->command('cpe:sync')->dailyAt('00:30')->withoutOverlapping();

        $schedule->call(function () {
            app(LicenseService::class)->checkOnline();
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

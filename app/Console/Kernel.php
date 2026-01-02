<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        Commands\Cleanup::class,
        Commands\CertificateExpiration::class,
        Commands\CVESearch::class,
        Commands\CPEImport::class
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cleanup old data
        $schedule->command('mercator:cleanup')
            ->name('cleanup-daily')
            ->daily();

        // Certificate expiration check
        $schedule->command('mercator:certificate-expiracy')
            ->daily();

        // CVE search
        $schedule->command('mercator:cve-search')
            ->name('cve-search-daily')
            ->daily();

        // CPE import
        $schedule->command('cpe:sync')
            ->name('cpe-sync-daily')
            ->dailyAt('00:30')
            ->withoutOverlapping();

        // Check license
        $schedule->command('license:check')
            ->dailyAt('00:30')
            ->name('validate-license-daily')
            ->withoutOverlapping(60);
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

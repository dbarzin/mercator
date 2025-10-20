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
        Commands\CertificateExpiracy::class,
        Commands\CVESearch::class,
        Commands\CPEImport::class,
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

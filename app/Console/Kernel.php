<?php

namespace App\Console;

use App\Certificate;

use \Carbon\Carbon;

use Illuminate\Support\Facades\Log;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Check for old certificates
        $schedule->call(function () {
            $certificates = Certificate::
                select('name','type','end_validity')
                ->where('end_validity', '>=', Carbon::now()->addDays(30)->toDateTimeString())
                ->get();

            if ($certificates->count()>0) {
                // send email alert
                $to_email = env('MAIL_ALERT','root');
                $subject = 'Certificates about to expire';
                $message = '<html><body>These certificates are about to exipre :<br><br>';
                foreach($certificates as $cert) {
                    $message .= $cert->name . " - " . $cert->type . " - ". $cert->end_validity ."<br>";
                }
                $message .= '</body></html>'; 

                 // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                 $headers[] = 'MIME-Version: 1.0';
                 $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                 // En-têtes additionnels
                 $headers[] = 'To: ' . $to_email;
                 $headers[] = 'From: mercator@localhost';
                 // $headers[] = 'Cc: anniversaire_archive@example.com';
                 // $headers[] = 'Bcc: anniversaire_verif@example.com';                
                mail($to_email, $subject, $message, implode("\r\n", $headers));
                }
            })
        // Run the task every week on Monday at 8:00
        // ->weeklyOn(1, '8:00');;
        ->  everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

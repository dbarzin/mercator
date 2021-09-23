<?php

namespace App\Console\Commands;

use App\Certificate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CertificateExpiracy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercator:certificate-expiracy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired certificates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $check_frequency = config('mercator-config.cert.check-frequency');

        if (($check_frequency === null) || ($check_frequency === '0')) {
            return;
        }
        if (
            // Dayly
            ($check_frequency === '1') ||
            // Monday
            (($check_frequency === '7') && (Carbon::now()->dayOfWeek === 1)) ||
            // 15 days
            (($check_frequency === '15') && ((Carbon::now()->day === 1) || (Carbon::now()->day === 15))) ||
            // Monthly
            (($check_frequency === '30') && (Carbon::now()->day === 1)) ||
            // 2 months
            (($check_frequency === '60') && ((Carbon::now()->day === 1) && (Carbon::now()->month % 2 === 0))) ||
            // 3 month
            (($check_frequency === '90') && ((Carbon::now()->day === 1) && (Carbon::now()->month % 3 === 0)))
        ) {
            // Check for old certificates
            $certificates = Certificate::
                select('name', 'type', 'end_validity')
                    ->where(
                    'end_validity',
                    '<=',
                    Carbon::now()
                        ->addDays(intval(config('mercator-config.cert.expire-delay')))
                        ->toDateString()
                )
                    ->get();

            $this->info($certificates->count() . ' certificate(s) will expire in '. config('mercator-config.cert.expire-delay') . ' days.');

            if ($certificates->count() > 0) {
                // send email alert
                $to_email = config('mercator-config.cert.mail-to');
                $subject = config('mercator-config.cert.mail-subject');
                $message = '<html><body>These certificates are about to exipre :<br><br>';
                foreach ($certificates as $cert) {
                    $message .= $cert->name . ' - ' . $cert->type . ' - '. $cert->end_validity .'<br>';
                }
                $message .= '</body></html>';

                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html;charset=iso-8859-1';

                // En-têtes additionnels
                $headers[] = 'From: '. config('mercator-config.cert.mail-from');
                if (mail($to_email, $subject, $message, implode("\r\n", $headers), ' -f'. config('mercator-config.cert.mail-from'))) {
                    $this->info('Mail sent to '.$to_email);
                } else {
                    $this->info('Email sending fail.');
                }
            }
        }
    }
}

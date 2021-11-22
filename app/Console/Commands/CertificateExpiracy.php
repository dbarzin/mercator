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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Log::debug("CertificateExpiracy - frequency ". $check_frequency);
        // Log::debug("CertificateExpiracy - day ". Carbon::now()->day);

	if ($this->needCheck()) {
            // Check for old certificates
            //
            // Log::debug("CertificateExpiracy - check");

            $certificates = Certificate::select('name', 'type', 'end_validity')
                ->where('end_validity', '<=', Carbon::now()
                ->addDays(intval(config('mercator-config.cert.expire-delay')))
                ->toDateString())
                ->get();

            $this->info(
                $certificates->count() .
                ' certificate(s) will expire in '.
                config('mercator-config.cert.expire-delay') .
                ' days.'
            );

            if ($certificates->count() > 0) {
                // send email alert
                $mail_from = config('mercator-config.cert.mail-from');
                $to_email = config('mercator-config.cert.mail-to');
                $subject = config('mercator-config.cert.mail-subject');
                $message = '<html><body>These certificates are about to exipre :<br><br>';
                foreach ($certificates as $cert) {
                    $message .= $cert->name . ' - ' . $cert->type . ' - '. $cert->end_validity .'<br>';
                }
                $message .= '</body></html>';

                // Define the header
                $headers = [
                    'MIME-Version: 1.0',
                    'Content-type: text/html;charset=iso-8859-1',
                    'From: '. $mail_from,
                ];

                // Send mail
                if (mail($to_email, $subject, $message, implode("\r\n", $headers), ' -f'. $mail_from)) {
                    $this->info('Mail sent to '.$to_email);
                } else {
                    $this->info('Email sending fail.');
                }
            }
        }
        // Log::debug("CertificateExpiracy - DONE.");
    }

    /**
     * return true if check is needed
     *
     * @return bool
     */
    private function needCheck()
    {
        $check_frequency = config('mercator-config.cert.check-frequency');

        return // Dayly
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
            (($check_frequency === '90') && ((Carbon::now()->day === 1) && (Carbon::now()->month % 3 === 0)));
    }
}

<?php

namespace App\Console\Commands;

use App\Certificate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::debug('CertificateExpiracy - day '. Carbon::now()->day);

        if ($this->needCheck()) {
            // Check for old certificates
            Log::debug('CertificateExpiracy - check');

            $certificates = Certificate::select('name', 'type', 'end_validity')
                ->where('status', 0)
                ->where('end_validity', '<=', Carbon::now()
                ->addDays(intval(config('mercator-config.cert.expire-delay')))->toDateString())
                ->orderBy('end_validity')
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
                    $message .= $cert->end_validity . ' - ' . $cert->name . ' - ' . $cert->type . '<br>';
                }
                $message .= '</body></html>';

                // print message
                // $this->info($message);

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
        Log::debug('CertificateExpiracy - DONE.');
    }

    /**
     * return true if check is needed
     *
     * @return bool
     */
    private function needCheck()
    {
        $check_frequency = config('mercator-config.cert.check-frequency');

        return // Daily
            ($check_frequency === '1') ||
            // Weekly
            (($check_frequency === '7') && (Carbon::now()->dayOfWeek === 1)) ||
            // Monthly
            (($check_frequency === '30') && (Carbon::now()->day === 1));
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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
        Log::debug('CertificateExpiracy - Start.');

        Log::debug('CertificateExpiracy - day '.Carbon::now()->day);

        // if (true) {
        if ($this->needCheck()) {
            // Check for old certificates
            Log::debug('CertificateExpiracy - check');

            $certificates = Certificate::where('status', 0)
                ->where('end_validity', '<=', Carbon::now()
                    ->addDays(intval(config('mercator-config.cert.expire-delay')))->toDateString())
                ->orderBy('end_validity')
                ->get();

            Log::debug(
                $certificates->count().
                ' certificate(s) will expire within '.
                config('mercator-config.cert.expire-delay').
                ' days.'
            );

            // check
            $repeat_notification = config('mercator-config.cert.repeat-notification');
            if (intval($repeat_notification) === 0) {
                Log::debug('CertificateExpiracy - remove cert aleady notified');
                foreach ($certificates as $key => $cert) {
                    if ($cert->last_notification === null) {
                        // never notified
                        Log::debug('CertificateExpiracy - '.$cert->name.' never notified.');
                        $cert->last_notification = now();
                        $cert->save();
                    } elseif (
                        Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $cert->last_notification)
                            ->greaterThan(
                                now()->addDays(-intval(config('mercator-config.cert.expire-delay')))
                            )
                    ) {
                        Log::debug('CertificateExpiracy - '.$cert->name.' already notified on '.$cert->last_notification);
                        $certificates->forget($key);
                    } else {
                        // must be notified
                        Log::debug('CertificateExpiracy - '.$cert->name.' kept.');
                        $cert->last_notification = now();
                        $cert->save();
                    }
                }
            } else {
                // set last notification for all certificates
                foreach ($certificates as $cert) {
                    $cert->last_notification = now();
                    $cert->save();
                }
            }

            if ($certificates->count() > 0) {
                // send email alert
                $mail_from = config('mercator-config.cert.mail-from');
                $to_email = config('mercator-config.cert.mail-to');
                $subject = config('mercator-config.cert.mail-subject');
                $group = config('mercator-config.cert.group');

                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();                               // Use SMTP
                    // Server settings
                    $mail->isSMTP();                                     // Use SMTP
                    $mail->Host = env('MAIL_HOST');               // Set the SMTP server
                    $mail->SMTPAuth = env('MAIL_AUTH');               // Enable SMTP authentication
                    $mail->Username = env('MAIL_USERNAME');           // SMTP username
                    $mail->Password = env('MAIL_PASSWORD');           // SMTP password
                    $mail->SMTPSecure = env('MAIL_SMTP_SECURE', false);  // Enable TLS encryption, `ssl` also accepted
                    $mail->SMTPAutoTLS = env('MAIL_SMTP_AUTO_TLS');      // Enable auto TLS
                    $mail->Port = env('MAIL_PORT');               // TCP port to connect to

                    // Recipients
                    $mail->setFrom($mail_from);
                    foreach (explode(',', $to_email) as $email) {
                        $mail->addAddress($email);
                    }

                    // Content
                    $mail->isHTML(true);                            // Set email format to HTML

                    // Optional: Add DKIM signing
                    $mail->DKIM_domain = env('MAIL_DKIM_DOMAIN');
                    $mail->DKIM_private = env('MAIL_DKIM_PRIVATE');
                    $mail->DKIM_selector = env('MAIL_DKIM_SELECTOR');
                    $mail->DKIM_passphrase = env('MAIL_DKIM_PASSPHRASE');
                    $mail->DKIM_identity = $mail->From;

                    if ($group === null || $group === '1') {
                        $mail->Subject = $subject;
                        $message = '<html><body>These certificates are about to exipre :<br><br>';
                        foreach ($certificates as $cert) {
                            $message .= $cert->end_validity.' - '.$cert->name.' - '.$cert->type.'<br>';
                        }
                        $message .= '</body></html>';

                        $mail->Body = $message;

                        // Send mail
                        $mail->send();

                        // Log
                        Log::debug("Mail sent to {$to_email}");
                    } else {
                        foreach ($certificates as $cert) {
                            $mail->Subject = $subject.' - '.$cert->end_validity.' - '.$cert->name;
                            $message = $cert->description;

                            // Send mail
                            $mail->Body = $message;

                            // Send mail
                            $mail->send();

                            // Log
                            Log::debug("Mail sent to {$to_email}");
                        }
                    }
                } catch (Exception $e) {
                    Log::error("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }
            }
        } else {
            Log::debug('CertificateExpiracy - no check');
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
        return true;
        $check_frequency = config('mercator-config.cert.check-frequency');

        return // Daily
            ($check_frequency === '1') ||
            // Weekly
            (($check_frequency === '7') && (Carbon::now()->dayOfWeek === 1)) ||
            // Monthly
            (($check_frequency === '30') && (Carbon::now()->day === 1));
    }
}

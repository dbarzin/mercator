<?php

namespace App\Console\Commands;

use App\MApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class CVESearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercator:cve-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for CVE';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('CVESearch - Start');
        Log::debug('CVESearch - day '. Carbon::now()->day);

        if (false) {
            //if ($this->needCheck()) {
            Log::debug('CVESearch - no check needed');
        } else {
            // Check for CVE
            Log::debug('CVESearch - check');

            // Be nice with CIRCL, wait few seconds !
            $seconds = rand(1, 600);
            Log::debug('CVESearch - wait ' . $seconds . 's');
            // sleep($seconds);

            Log::debug('CVESearch - check');

            $provider = config('mercator-config.cve.provider');
            $check_frequency = config('mercator-config.cve.check-frequency');

            // update provider
            if (($provider === 'https://cvepremium.circl.lu') || ($provider === 'https//cve.circl.lu')) {
                // change variable
                $provider = 'https://vulnerability.circl.lu';

                config(['mercator-config.cve.provider' => $provider]);

                // Save configuration
                $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
                file_put_contents(config_path('mercator-config.php'), $text);
            }

            $client = curl_init($provider . '/api/dbInfo');
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($client);
            if ($response === false) {
                Log::debug("CVESearch - Could not connect to provider {$provider}");
                return;
            }

            $json = json_decode($response);
            $msg = 'Last NVD update :' . $json->last_updates->nvd . ' Total db size = ' . $json->db_sizes->total;
            Log::debug('CVESearch - ' . $msg);

            // start timestamp
            $min_timestamp = date('Y-m-d', strtotime(sprintf('-%d days', $check_frequency), strtotime('now')));
            Log::debug('CVESearch - Check CVE published before '.   $min_timestamp);
            print_r("min_timestamp : {$min_timestamp}");

            // CVE counters
            $cpe_match = [];

            // loop on applications
            $applications = DB::table('m_applications')
                ->select('name', 'vendor', 'product', 'version')
                ->whereNotNull('vendor')
                ->whereNotNull('product')
                ->orderBy('name')
                ->get();

            /*
                        foreach ($applications as $app) {
                            $url = $provider . '/api/cvefor/cpe:2.3:a:' . $app->vendor . ':' . $app->product . ':' . $app->version;

                            Log::debug('CVEReport - url ' . $url);

                            $http = curl_init($url);
                            curl_setopt($http, CURLOPT_RETURNTRANSFER, true);

                            $response = curl_exec($http);
                            if ($response === false) {
                                Log::debug('CVESearch - Could not query the provider');
                                return;
                            }

                            $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
                            Log::debug('CVEReport - status ' . $http_status);
                            curl_close($http);

                            $json = json_decode($response);

                            // loop on all CVE
                            if ($http_status === 200) {
                                // loop on all CVE
                                foreach ($json as $cve) {
                                    // check CVE in frequency range
                                    if (strtotime($cve->Published) >= $min_timestamp) {
                                        // put summary in lowercase
                                        $cve->summary = strtolower($cve->summary);
                                        // Log::debug('CVESearch - CVE summary ' . $cve->summary);
                                        $cve->application = $name;
                                        $cve_match[] = $cve;
                                    }
                                }
                            }
                            // Be nice with CIRCL, wait few miliseconds
                            usleep(200);
                        }
            */
            // QUERY
            $client = curl_init($provider . '/api/last');
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($client);
            if ($response === false) {
                Log::debug('CVESearch - Could not query the provider');
                return;
            }
            $json = json_decode($response);

            // get application names in lowercase
            $names = MApplication::all()
                ->sortBy('name')
                ->pluck('name')
                ->map(function ($name) {
                    return strtolower($name);
                });

            // CVE counters
            $found = false;
            $message = '<html><body>';

            // loop on all CVE
            foreach ($json as $cve) {
                Log::debug("Check CVE");
                // check CVE in frequency range
                if (property_exists($cve, 'dataType') && $cve->dataType === 'CVE_RECORD') {
                    if (substr($cve->cveMetadata->datePublished,0,10) >= $min_timestamp) {
                        // put summary in lowercase
                        $text = strtolower($cve->containers->cna->title);
                        Log::debug('CVESearch - CVE text ' . $text);
                        foreach ($names as $name) {
                            Log::debug('CVESearch - check ' . $name);
                            if (str_contains($text, $name)) {
                                Log::debug('CVESearch - found ' . $name);
                                $message .= '<b>' . $name . ' </b> : <b>' . $cve->cveMetadata->cveId . ' </b> - ' . $cve->details . '<br>';
                                $found = true;
                            }
                        }
                    }
                    else {
                        Log::debug('CVESearch - skip old ' . $cve->cveMetadata->datePublished);
                    }
                } elseif (property_exists($cve, 'details') && property_exists($cve, 'published')) {
                    if (substr($cve->published,0,10) >= $min_timestamp) {
                        // put summary in lowercase
                        $text = strtolower($cve->details);
                        Log::debug('CVESearch - CVE text ' . $text);
                        foreach ($names as $name) {
                            if (str_contains($text, $name)) {
                                Log::debug('CVESearch - found ' . $name);
                                $message .= '<b>' . $name . ' </b> : <b>' . $cve->aliases[0] . ' </b> - ' . $cve->details . '<br>';
                                $found = true;
                            }
                        }
                    }
                    else {
                        Log::debug('CVESearch - skip old ' . $cve->published);
                    }
                } elseif (property_exists($cve, 'document') &&
                        property_exists($cve->document, 'category') &&
                        ($cve->document->category === 'csaf_security_advisory')) {
                    if (substr($cve->document->tracking->current_release_date,0,10) >= $min_timestamp) {
                        // put summary in lowercase
                        $text = strtolower($cve->document->title);
                        Log::debug('CVESearch - CVE text ' . $text);
                        foreach ($names as $name) {
                            if (str_contains($text, $name)) {
                                Log::debug('CVESearch - found ' . $name);
                                $message .= '<b>' . $name . ' </b> : <b>' . $cve->document->title . ' </b> - ' . $cve->document->notes[0]->text . '<br>';
                                $found = true;
                            }
                        }
                    }
                    else {
                        Log::debug('CVESearch - skip old ' . $cve->document->tracking->current_release_date);
                    }
                } elseif (property_exists($cve, 'circl')) {
                    // SKIP
                    Log::error('Unknown circl format !');
                } else {
                    Log::error('Unknown CVE format !');
                    print_r("Unknwon CVE format:\n");
                    print_r($cve);
                }
            }
            $message .= '</body></html>';

            if ($found) {
                Log::debug("CVESearch - Message {$message}");

                // Send mail
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
                    $mail->setFrom(config('mercator-config.cve.mail-from'));
                    foreach (explode(',', config('mercator-config.cve.mail-to')) as $email) {
                        $mail->addAddress($email);
                    }

                    // Content
                    $mail->isHTML(true);                            // Set email format to HTML
                    $mail->Subject = config('mercator-config.cve.mail-subject');

                    // Optional: Add DKIM signing
                    $mail->DKIM_domain = env('MAIL_DKIM_DOMAIN');
                    $mail->DKIM_private = env('MAIL_DKIM_PRIVATE');
                    $mail->DKIM_selector = env('MAIL_DKIM_SELECTOR');
                    $mail->DKIM_passphrase = env('MAIL_DKIM_PASSPHRASE');
                    $mail->DKIM_identity = $mail->From;

                    $mail->Body = $message;

                    // Send mail
                    $mail->send();

                    // Log
                    Log::debug('CVESearch - Mail sent');
                } catch (Exception $e) {
                    Log::error("CVESearch - Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }
            }
        }
        Log::debug('CVESearch - DONE.');
    }

    /**
     * return true if check is needed
     *
     * @return bool
     */
    private function needCheck()
    {
        $check_frequency = config('mercator-config.cve.check-frequency');

        Log::debug('CVESearch - check-frequency '. $check_frequency);

        return // Daily
            ($check_frequency === 1) ||
            // Weekly
            (($check_frequency === 7) && (Carbon::now()->dayOfWeek === 1)) ||
            // Monthly
            (($check_frequency === 30) && (Carbon::now()->day === 1));
    }
}

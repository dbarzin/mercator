<?php

namespace App\Console\Commands;

use App\MApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            $seconds = rand(1,600);
            Log::debug('CVESearch - wait ' . $seconds . 's');
            // sleep($seconds);

            Log::debug('CVESearch - check');

            $provider = config('mercator-config.cve.provider');
            $check_frequency = config('mercator-config.cve.check-frequency');

            // update provider
            if ($provider === 'https//cve.circl.lu') {
                // change variable
                $provider = "https://cvepremium.circl.lu";
                config(['mercator-config.cve.provider' => $provider]);

                // Save configuration
                $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
                file_put_contents(config_path('mercator-config.php'), $text);
                }

            $client = curl_init($provider . '/api/dbinfo');
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($client);
            if ($response === false) {
                Log::debug('CVESearch - Could not connect to provider');
                return;
            }

            $json = json_decode($response);
            Log::debug('CVESearch - Provider last update: ' . $json->cwe->last_update . ' size=' . $json->cwe->size);

            // start timestamp
            $min_timestamp = strtotime(sprintf('-%d days', $check_frequency), strtotime('now'));
            Log::debug('CVESearch - Check CVE published before '.date('l dS \o\f F Y h:i:s A', $min_timestamp));

            // CVE counters
            $cpe_match = [];

            // loop on applications
            $applications = DB::table('m_applications')
                ->select('name', 'vendor', 'product', 'version')
                ->whereNotNull('vendor')
                ->whereNotNull('product')
                ->orderBy('name')
                ->get();

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

            // QUERY
            $client = curl_init($provider . '/api/query');
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
            $cve_match = [];

            // loop on all CVE
            foreach ($json->results as $cve) {
                // check CVE in frequency range
                if (strtotime($cve->Published) >= $min_timestamp) {
                    // put summary in lowercase
                    $cve->summary = strtolower($cve->summary);
                    // Log::debug('CVESearch - CVE summary ' . $cve->summary);
                    foreach ($names as $name) {
                        // Log::debug('CVESearch - check ' . $name);
                        if (str_contains($cve->summary, $name)) {
                            $cve->application = $name;
                            $cve_match[] = $cve;
                        }
                    }
                }
            }

            Log::debug('CVESearch - ' . count($cve_match) . ' match found');

            // compose message
            if ((count($cpe_match) > 0) || (count($cve_match) > 0)) {
                // send email alert
                $mail_from = config('mercator-config.cve.mail-from');
                $to_email = config('mercator-config.cve.mail-to');
                $subject = config('mercator-config.cve.mail-subject');

                // set mail header
                $headers = [
                    'MIME-Version: 1.0',
                    'Content-type: text/html;charset=iso-8859-1',
                    'From: '. $mail_from,
                ];

                $message = '<html><body>';
                if (count($cpe_match)>0) {
                    $message = '<h1>CPE Matching</h1>';
                    foreach ($cpe_match as $cve) {
                        $message .= '<b>' . $cve->application . ' </b> : <b>' . $cve->id . ' </b> - ' . $cve->summary . '<br>';
                       }
                    }
                if (count($cve_match)>0) {
                    $message = '<h1>String matching</h1>';
                    foreach ($cve_match as $cve) {
                        $message .= '<b>' . $cve->application . ' </b> : <b>' . $cve->id . ' </b> - ' . $cve->summary . '<br>';
                        }
                    }
                $message .= '</body></html>';

                // Log::debug('CVESearch - '. $message);

                // Send mail
                if (mail($to_email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, implode("\r\n", $headers), ' -f'. $mail_from)) {
                    Log::debug('Mail sent to '.$to_email);
                } else {
                    Log::debug('Email sending fail.');
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

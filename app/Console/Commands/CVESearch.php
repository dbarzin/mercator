<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\MApplication;

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

        if ($this->needCheck()) {
            Log::debug('CVESearch - no check needed');
        }
        else {
            // Check for CVE
            Log::debug('CVESearch - check');

            $provider = config('mercator-config.cve.provider');

            $client = curl_init($provider . "/api/dbInfo");
            curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($client);
            if ($response == false) {
                Log::debug('CVESearch - Could not connect to provider');
                return;
            }

            $json = json_decode($response);
            Log::debug("CVESearch - Provider last update: " . $json->cwe->last_update . " size=" . $json->cwe->size);

            $client = curl_init($provider . "/api/query");
            curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($client);
            if ($response == false) {
                Log::debug('CVESearch - Could not query the provider');
                return;
            }
            
            $json = json_decode($response);

            // get application names in lowercase
            $names = MApplication::all()
                ->sortBy('name')
                ->pluck('name')
                ->map(function ($name) {
                    return strtoLower($name);
                });


            // start timestamp
            $min_timestamp = strtotime(sprintf('-%d days', $check_frequency),strtotime("now"));
            Log::debug('CVESearch - Check CVE published before '.date('l dS \o\f F Y h:i:s A',$min_timestamp));

            // CVE counters
            $cve_count = 0;
            $cve_match = [];

            // loop on all CVE
            foreach ($json->results as $cve) {
                // check CVE in frequency range
                if (strtotime($cve->Published) >= $min_timestamp) {
                    $cve_count += 1;
                    // check application name present in cve summary
                    $found = false;
                    // put summary in lowercase
                    $cve->summary = strtolower($cve->summary);
                    // Log::debug('CVESearch - CVE summary ' . $cve->summary);
                    foreach ($names as $name) {
                        // Log::debug('CVESearch - check ' . $name);
                        if (str_contains($cve->summary,$name)) {
                            $found=true;
                            break;
                            }
                        }
                    if ($found) {
                        $cve_match[] = $cve;
                    }
                }
            }

            Log::debug('CVESearch - ' . count($cve_match) . ' match found');

            // compose message
            if (count($cve_match) > 0) {

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
                foreach ($cve_match as $cve) {
                    $message .= $cve->id . ' - ' . $cve->summary . '<br>';
                }
                $message .= '</body></html>';

                Log::debug('CVESearch - '. $message);

                // Send mail
                if (mail($to_email, $subject, $message, implode("\r\n", $headers), ' -f'. $mail_from)) {
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

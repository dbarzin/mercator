<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Mercator\Core\License\LicenseToken;
use Mercator\Core\Models\LocalLicense;

class MercatorLicense extends Command
{
    protected $signature = 'mercator:license';
    protected $description = 'Show Mercator license information and check it online';

    public function handle(LicenseToken $licenseToken): int
    {
        $this->info('Mercator license status');
        $this->line('-----------------------');

        // 1. Récupérer le token local
        $local = LocalLicense::query()->first();

        if (! $local) {
            // Pas encore en DB → on regarde dans l’ENV
            $envToken = config('app.license');
            
            if (! $envToken) {
                $this->error('No license token found. Set APP_LICENSE in your .env file.');
                return self::FAILURE;
            }

            $local = LocalLicense::query()->create([
                'license_token' => $envToken,
            ]);
        }

        $token = $local->license_token;

        // 2. Vérification offline (signature + dates)
        $this->line('Offline validation:');

        if (! $licenseToken->verify($token)) {
            $this->error('  ✖ Invalid token signature (license may be corrupted or forged).');
            return self::FAILURE;
        }

        $data = $licenseToken->decode($token);

        if (! $data) {
            $this->error('  ✖ Unable to decode license payload.');
            return self::FAILURE;
        }

        // Afficher les infos du payload
        $this->line('  ✔ Token signature valid.');
        $this->line('');
        $this->info('License payload:');
        $this->line('  Customer   : '.($data['customer'] ?? 'n/a'));
        if (!empty($data['email'])) {
            $this->line('  Email      : '.$data['email']);
        }
        $this->line('  License ID : '.($data['lic'] ?? 'n/a'));

        $modules = $data['modules'] ?? null;
        if ($modules === null) {
            $this->line('  Modules    : ALL');
        } else {
            $this->line('  Modules    : '.implode(', ', $modules));
        }

        $exp       = isset($data['exp']) ? Carbon::parse($data['exp']) : null;
        $graceDays = (int)($data['grace_days'] ?? 0);

        if ($exp) {
            $this->line('  Expires at : '.$exp->toDateTimeString().' (UTC)');
            $this->line('  Grace days : '.$graceDays);
            $graceUntil = $exp->clone()->addDays($graceDays);
            $this->line('  Grace until: '.$graceUntil->toDateTimeString().' (UTC)');

            if (now()->lessThanOrEqualTo($graceUntil)) {
                $this->info('  ✔ Offline status: VALID (within expiration or grace period).');
            } else {
                $this->error('  ✖ Offline status: EXPIRED (beyond grace period).');
            }
        } else {
            $this->line('  Expires at : n/a');
            $this->info('  ✔ Offline status: VALID (no expiration date in payload).');
        }

        $this->line('');

        // 3. Vérification en ligne (non bloquante pour l’application)
        $this->info('Online validation:');

        $checkUrl = config('mercator.license_check_url');

        if (! $checkUrl) {
            $this->warn('  ! No license_check_url configured (config("mercator.license_check_url")). Skipping online check.');
            return self::SUCCESS;
        }

        try {
            $response = Http::timeout(5)
                ->acceptJson()
                ->get($checkUrl, [
                    'token' => $token,
                ]);

            if (! $response->ok()) {
                $this->warn('  ! License server responded with status '.$response->status().'.');
                $local->last_check_at = now();
                $local->last_check_status = 'error';
                $local->last_check_error = 'HTTP '.$response->status();
                $local->save();
                return self::SUCCESS;
            }

            $dataServer = $response->json();

            $valid  = $dataServer['valid']  ?? false;
            $reason = $dataServer['reason'] ?? null;

            $local->last_check_at    = now();
            $local->last_check_status = $valid ? 'ok' : 'expired';
            $local->last_check_error  = $reason;
            $local->save();

            if ($valid) {
                $this->info('  ✔ License server: VALID');
            } else {
                $this->error('  ✖ License server: INVALID or EXPIRED');
                if ($reason) {
                    $this->line('    Reason: '.$reason);
                }
            }

            if (!empty($dataServer['expires_at'])) {
                $this->line('  Server expiry : '.$dataServer['expires_at']);
            }
            if (!empty($dataServer['modules'])) {
                $this->line('  Server modules: '.implode(', ', $dataServer['modules']));
            }

        } catch (\Throwable $e) {
            $this->warn('  ! Error contacting license server: '.$e->getMessage());
            $local->last_check_at    = now();
            $local->last_check_status = 'error';
            $local->last_check_error  = $e->getMessage();
            $local->save();
        }

        $this->line('');
        $this->info('Done.');

        return self::SUCCESS;
    }
}

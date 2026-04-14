<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mercator\Core\Models\Document;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ConfigurationController extends Controller
{
    /**
     * Page de configuration unifiée — charge tous les onglets.
     * Lit directement depuis le fichier pour garantir les valeurs à jour
     * après un redirect (le runtime config est chargé une seule fois au boot).
     */
    public function getParameters(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cfg = $this->readConfigFile();

        return view('admin.config.parameters', [
            // Général
            'security_need_auth'       => $cfg['parameters']['security_need_auth'] ?? false,
            // Certificats
            'cert_mail_from'           => $cfg['cert']['mail-from']                     ?? '',
            'cert_mail_to'             => $cfg['cert']['mail-to']                       ?? '',
            'cert_mail_subject'        => $cfg['cert']['mail-subject']                  ?? '',
            'cert_check_frequency'     => $cfg['cert']['check-frequency']               ?? '0',
            'cert_expire_delay'        => $cfg['cert']['expire-delay']                  ?? '30',
            'cert_group'               => (string) ($cfg['cert']['group']               ?? '0'),
            'cert_repeat_notification' => (string) ($cfg['cert']['repeat-notification'] ?? '0'),
            // CVE
            'cve_mail_from'            => $cfg['cve']['mail-from']                      ?? '',
            'cve_mail_to'              => $cfg['cve']['mail-to']                        ?? '',
            'cve_mail_subject'         => $cfg['cve']['mail-subject']                   ?? '',
            'cve_check_frequency'      => $cfg['cve']['check-frequency']                ?? '0',
            'cve_provider'             => $cfg['cve']['provider']                       ?? '',
            // Documents
            'count' => Document::count(),
            'sum'        => Document::sum('size'),
            'active_tab' => $request->query('tab', 'general'),
        ]);
    }

    /**
     * Point d'entrée unique pour toutes les sauvegardes.
     * L'onglet actif est transmis via le champ caché `active_tab`.
     * Le redirect utilise un fragment d'URL (#tab-xxx) pour restaurer l'onglet
     * côté JS — plus fiable que le flash session.
     */
    public function saveConfig(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tab    = $request->input('active_tab', 'general');
        $action = $request->input('action', 'save');

        [$msg, $ok] = match ($tab) {
            'cert' => $this->handleCert($action, $request),
            'cve'  => $this->handleCve($action, $request),
            default=> $this->handleGeneral($request),
        };

        // Fragment #tab-xxx : repris par location.hash dans le JS de la blade.
        return redirect(route('admin.config.parameters') . '?tab=' . $tab)
            ->with($ok ? 'success' : 'error', $msg);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Handlers par onglet
    // ─────────────────────────────────────────────────────────────────────────

    private function handleGeneral(Request $request): array
    {
        $cfg = $this->readConfigFile();
        $cfg['parameters']['security_need_auth'] = $request->boolean('security_need_auth');
        $this->writeConfigFile($cfg);

        return [trans('global.saved'), true];
    }

    private function handleCert(string $action, Request $request): array
    {
        if ($action === 'save') {
            $cfg = $this->readConfigFile();
            $cfg['cert']['mail-from']           = $request->input('mail_from');
            $cfg['cert']['mail-to']             = $request->input('mail_to');
            $cfg['cert']['mail-subject']        = $request->input('mail_subject');
            $cfg['cert']['check-frequency']     = $request->input('check_frequency');
            $cfg['cert']['expire-delay']        = $request->input('expire_delay');
            $cfg['cert']['group']               = $request->input('group');
            $cfg['cert']['repeat-notification'] = $request->input('repeat-notification');
            $this->writeConfigFile($cfg);

            return [trans('global.saved'), true];
        }

        return $this->sendTestMail(
            $request->input('mail_from'),
            $request->input('mail_to'),
            $request->input('mail_subject'),
        );
    }

    private function handleCve(string $action, Request $request): array
    {
        if ($action === 'save') {
            $cfg = $this->readConfigFile();
            $cfg['cve']['mail-from']       = $request->input('mail_from');
            $cfg['cve']['mail-to']         = $request->input('mail_to');
            $cfg['cve']['mail-subject']    = $request->input('mail_subject');
            $cfg['cve']['check-frequency'] = $request->input('check_frequency');
            $cfg['cve']['provider']        = $request->input('provider');
            $this->writeConfigFile($cfg);

            return [trans('global.saved'), true];
        }

        if ($action === 'test_provider') {
            return $this->testProvider($request->input('provider'));
        }

        return $this->sendTestMail(
            $request->input('mail_from'),
            $request->input('mail_to'),
            $request->input('mail_subject'),
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Lecture / écriture du fichier de config
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Lit le fichier config/mercator.php en court-circuitant OPcache.
     */
    private function readConfigFile(): array
    {
        $path = config_path('mercator.php');

        if (!file_exists($path)) {
            return [];
        }

        // Invalide OPcache avant require pour être sûr de lire la version sur disque.
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($path, true);
        }

        return require $path;
    }

    /**
     * Écrit le tableau de config dans config/mercator.php.
     */
    private function writeConfigFile(array $cfg): void
    {
        $path = config_path('mercator.php');

        file_put_contents($path, '<?php return ' . var_export($cfg, true) . ';');

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($path, true);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Utilitaires mail / provider
    // ─────────────────────────────────────────────────────────────────────────

    /** @return array{0: string, 1: bool} */
    private function sendTestMail(string $from, string $to, string $subject): array
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host        = config('mail.mailers.smtp.host');
            $mail->SMTPAuth    = config('mail.mailers.smtp.username') !== null;
            $mail->Username    = config('mail.mailers.smtp.username');
            $mail->Password    = config('mail.mailers.smtp.password');
            $mail->SMTPSecure  = config('mail.mailers.smtp.encryption');
            $mail->SMTPAutoTLS = config('mail.mailers.smtp.auto_tls');
            $mail->Port        = (int) config('mail.mailers.smtp.port');

            $mail->setFrom($from);
            foreach (explode(',', $to) as $email) {
                $mail->addAddress(trim($email));
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = '<html><body><br>This is a test message !<br><br></body></html>';

            $mail->DKIM_domain     = config('mail.dkim.domain');
            $mail->DKIM_private    = config('mail.dkim.private');
            $mail->DKIM_selector   = config('mail.dkim.selector');
            $mail->DKIM_passphrase = config('mail.dkim.passphrase');
            $mail->DKIM_identity   = $mail->From;

            $mail->send();

            return ['Message has been sent', true];
        } catch (Exception) {
            return ["Message could not be sent. Mailer Error: {$mail->ErrorInfo}", false];
        }
    }

    /** @return array{0: string, 1: bool} */
    private function testProvider(string $provider): array
    {
        $client = curl_init($provider . '/api/dbInfo');
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($client);
        curl_close($client);

        if ($response === false) {
            return ['Could not connect to provider', false];
        }
        $json = json_decode($response);
        if ($json === null) {
            return ['Could not connect to provider (invalid JSON)', false];
        }

        return [
            'Last NVD update: ' . $json->last_updates->nvd
            . ' — Total db size = ' . $json->db_sizes->total,
            true,
        ];
    }
}
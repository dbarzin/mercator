<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ConfigurationController extends Controller
{
    /*
    * Return the Certificate configuration
    */
    public function getCertConfig()
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get configuration
        $mail_from = config('mercator-config.cert.mail-from');
        $mail_to = config('mercator-config.cert.mail-to');
        $mail_subject = config('mercator-config.cert.mail-subject');
        $check_frequency = config('mercator-config.cert.check-frequency');
        $expire_delay = config('mercator-config.cert.expire-delay');
        $group = config('mercator-config.cert.group');
        $repeat_notification = config('mercator-config.cert.repeat-notification');

        // Return
        return view(
            'admin.config.cert',
            compact(
                'mail_from',
                'mail_to',
                'mail_subject',
                'check_frequency',
                'expire_delay',
                'group',
                'repeat_notification'
            )
        );
    }

    /*
    * Save the Mail Certificate configuration
    */
    public function saveCertConfig(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // read request
        $mail_from = request('mail_from');
        $mail_to = request('mail_to');
        $mail_subject = request('mail_subject');
        $check_frequency = request('check_frequency');
        $expire_delay = request('expire_delay');
        $group = request('group');
        $repeat_notification = request('repeat-notification');

        switch ($request->input('action')) {
            case 'save':
                // put in config file
                config(['mercator-config.cert.mail-from' => $mail_from]);
                config(['mercator-config.cert.mail-to' => $mail_to]);
                config(['mercator-config.cert.mail-subject' => $mail_subject]);
                config(['mercator-config.cert.check-frequency' => $check_frequency]);
                config(['mercator-config.cert.expire-delay' => $expire_delay]);
                config(['mercator-config.cert.group' => $group]);
                config(['mercator-config.cert.repeat-notification' => $repeat_notification]);

                // Save configuration
                $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
                file_put_contents(config_path('mercator-config.php'), $text);

                // Return
                $msg = 'Configuration saved !';
                break;
            case 'test':
                // Create a new PHPMailer instance
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
                    foreach (explode(',', $mail_to) as $email) {
                        $mail->addAddress(trim($email));
                    }

                    // Content
                    $mail->isHTML(true);                            // Set email format to HTML
                    $mail->Subject = $mail_subject;
                    $mail->Body = '<html><body><br>This is a test message !<br><br></body></html>';

                    // Optional: Add DKIM signing
                    $mail->DKIM_domain = env('MAIL_DKIM_DOMAIN');
                    $mail->DKIM_private = env('MAIL_DKIM_PRIVATE');
                    $mail->DKIM_selector = env('MAIL_DKIM_SELECTOR');
                    $mail->DKIM_passphrase = env('MAIL_DKIM_PASSPHRASE');
                    $mail->DKIM_identity = $mail->From;

                    // Send email
                    $mail->send();

                    $msg = 'Message has been sent';
                } catch (Exception $e) {
                    $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                break;
            default:
                $msg = 'no actions made.';
        }
        return view(
            'admin.config.cert',
            compact(
                'mail_from',
                'mail_to',
                'mail_subject',
                'check_frequency',
                'expire_delay',
                'group',
                'repeat_notification'
            )
        )
            ->withErrors($msg);
    }

    /*
    * Return the CVE configuration
    */
    public function getCVEConfig()
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get configuration
        $mail_from = config('mercator-config.cve.mail-from');
        $mail_to = config('mercator-config.cve.mail-to');
        $mail_subject = config('mercator-config.cve.mail-subject');
        $check_frequency = config('mercator-config.cve.check-frequency');
        $provider = config('mercator-config.cve.provider');

        // Return
        return view(
            'admin.config.cve',
            compact('mail_from', 'mail_to', 'mail_subject', 'check_frequency', 'provider')
        );
    }

    /*
    * Save the CVE configuration
    */
    public function saveCVEConfig(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // read request
        $mail_from = request('mail_from');
        $mail_to = request('mail_to');
        $mail_subject = request('mail_subject');
        $check_frequency = request('check_frequency');
        $provider = request('provider');

        switch ($request->input('action')) {
            case 'save':
                // put in config file
                config(['mercator-config.cve.mail-from' => $mail_from]);
                config(['mercator-config.cve.mail-to' => $mail_to]);
                config(['mercator-config.cve.mail-subject' => $mail_subject]);
                config(['mercator-config.cve.check-frequency' => $check_frequency]);
                config(['mercator-config.cve.provider' => $provider]);

                // Save configuration
                $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
                file_put_contents(config_path('mercator-config.php'), $text);

                // Return
                $msg = 'Configuration saved !';
                break;
            case 'test':
                // Create a new PHPMailer instance
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
                    foreach (explode(',', $mail_to) as $email) {
                        $mail->addAddress($email);
                    }

                    // Content
                    $mail->isHTML(true);                            // Set email format to HTML
                    $mail->Subject = $mail_subject;
                    $mail->Body = '<html><body><br>This is a test message !<br><br></body></html>';

                    // Optional: Add DKIM signing
                    $mail->DKIM_domain = env('MAIL_DKIM_DOMAIN');
                    $mail->DKIM_private = env('MAIL_DKIM_PRIVATE');
                    $mail->DKIM_selector = env('MAIL_DKIM_SELECTOR');
                    $mail->DKIM_passphrase = env('MAIL_DKIM_PASSPHRASE');
                    $mail->DKIM_identity = $mail->From;

                    // Send email
                    $mail->send();

                    $msg = 'Message has been sent';
                } catch (Exception $e) {
                    $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                break;
            case 'test_provider':
                $client = curl_init($provider . '/api/dbInfo');
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($client);
                if ($response === false) {
                    $msg = 'Could not connect to provider';
                } else {
                    $json = json_decode($response);
                    if ($json === null) {
                        $msg = 'Could not connect to provider';
                    } else {
                        //dd($json);
                        $msg = 'Last NVD update :' . $json->last_updates->nvd . ' Total db size = ' . $json->db_sizes->total;
                    }
                }
                break;

            default:
                $msg = 'no actions made.';
        }
        return view(
            'admin.config.cve',
            compact('mail_from', 'mail_to', 'mail_subject', 'check_frequency', 'provider')
        )
            ->withErrors($msg);
    }

    /*
    * Return the parameters
    */
    public function getParameters()
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get configuration
        $security_need_auth = config('mercator-config.parameters.security_need_auth');

        // Return
        return view(
            'admin.config.parameters',
            compact('security_need_auth')
        );
    }

    /*
    * Save the parameters
    */
    public function saveParameters(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // read request
        $security_need_auth = request('security_need_auth');

        // Save parameters
        config(['mercator-config.parameters.security_need_auth' => $security_need_auth]);

        // Save configuration
        $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
        file_put_contents(config_path('mercator-config.php'), $text);

        // Return
        return view(
            'admin.config.parameters',
            compact('security_need_auth')
        )
            ->withErrors('Configuration saved !');
    }
}

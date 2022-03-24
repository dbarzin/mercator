<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get configuration
        $mail_from = config('mercator-config.cert.mail-from');
        $mail_to = config('mercator-config.cert.mail-to');
        $mail_subject = config('mercator-config.cert.mail-subject');
        $check_frequency = config('mercator-config.cert.check-frequency');
        $expire_delay = config('mercator-config.cert.expire-delay');
        $group = config('mercator-config.cert.group');

        // dd($mail_from);

        // Return
        return view(
            'admin.configuration',
            compact('mail_from', 'mail_to', 'mail_subject', 'check_frequency', 'expire_delay', 'group')
        );
    }

    public function save(Request $request)
    {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // read request
        $mail_from = request('mail_from');
        $mail_to = request('mail_to');
        $mail_subject = request('mail_subject');
        $check_frequency = request('check_frequency');
        $expire_delay = request('expire_delay');
        $group = request('group');

        switch ($request->input('action')) {
            case 'save':
                // put in config file
                config(['mercator-config.cert.mail-from' => $mail_from]);
                config(['mercator-config.cert.mail-to' => $mail_to]);
                config(['mercator-config.cert.mail-subject' => $mail_subject]);
                config(['mercator-config.cert.check-frequency' => $check_frequency]);
                config(['mercator-config.cert.expire-delay' => $expire_delay]);
                config(['mercator-config.cert.group' => $group]);

                // Save configuration
                $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
                file_put_contents(config_path('mercator-config.php'), $text);

                // Return
                $msg = 'Configuration saved !';
                break;
            case 'test':
                // send test email alert
                $message = '<html><body><br>This is a test message !<br><br></body></html>';

                // define the header
                $headers = [
                    'MIME-Version: 1.0',
                    'Content-type: text/html;charset=iso-8859-1',
                    'From: '. $mail_from,
                ];

                // En-têtes additionnels
                if (mail($mail_to, 'Test: ' . $mail_subject, $message, implode("\r\n", $headers), ' -f'. $mail_from)) {
                    $msg = 'Mail sent to '.$mail_to;
                } else {
                    $msg = 'Email sending fail.';
                }
                break;
            default:
                $msg = "no actions made.";
        }
        return view(
            'admin.configuration',
            compact('mail_from', 'mail_to', 'mail_subject', 'check_frequency', 'expire_delay','group')
        )
            ->withErrors($msg);
    }
}

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
        $cleanup_delay=config('mercator-config.cleanup-delay');        
        $mail_from=config('mercator-config.cert.mail-from');
        $mail_to=config('mercator-config.cert.mail-to');
        $mail_subject=config('mercator-config.cert.mail-subject');
        $check_frequency=config('mercator-config.cert.check-frequency');
        $expire_delay=config('mercator-config.cert.expire-delay');

        // dd($mail_from);

        // Return
        return view('admin.configuration',
            compact('cleanup_delay','mail_from','mail_to','mail_subject','check_frequency','expire_delay'));
    }

    public function save(Request $request) {
        abort_if(Gate::denies('configure'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // read request
        $cleanup_delay=request('cleanup_delay');        
        $mail_from=request('mail_from');
        $mail_to=request('mail_to');
        $mail_subject=request('mail_subject');
        $check_frequency=request('check_frequency');
        $expire_delay=request('expire_delay');

        // put in config file
        config(['mercator-config.cleanup.delay' => $cleanup_delay]);
        config(['mercator-config.cert.mail-from' => $mail_from]);
        config(['mercator-config.cert.mail-to' => $mail_to]);
        config(['mercator-config.cert.mail-subject' => $mail_subject]);
        config(['mercator-config.cert.check-frequency' => $check_frequency]);
        config(['mercator-config.cert.expire-delay' => $expire_delay]);

        // Save configuration
        $text = '<?php return ' . var_export(config('mercator-config'), true) . ';';
        file_put_contents(config_path('mercator-config.php'), $text);

        // TODO : message config saved 
        // Return
        return view('admin.configuration',
            compact('cleanup_delay','mail_from','mail_to','mail_subject','check_frequency','expire_delay'))
            ->withErrors('Configuration saved !');
    }

}

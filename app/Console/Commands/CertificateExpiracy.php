<?php
 
namespace App\Console\Commands;

use App\Certificate;

use \Carbon\Carbon;

// use Illuminate\Support\Facades\Log;
 
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
 
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
     
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Check for old certificates
        /*
        $schedule->call(function () {
            $certificates = Certificate::
                select('name','type','end_validity')
                ->where('end_validity', '>=', Carbon::now()->addDays(30)->toDateTimeString())
                ->get();

            if ($certificates->count()>0) {
                // send email alert
                $to_email = env('MAIL_ALERT','root');
                $subject = 'Certificates about to expire';
                $message = '<html><body>These certificates are about to exipre :<br><br>';
                foreach($certificates as $cert) {
                    $message .= $cert->name . " - " . $cert->type . " - ". $cert->end_validity ."<br>";
                }
                $message .= '</body></html>'; 

                 // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                 $headers[] = 'MIME-Version: 1.0';
                 $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                 // En-têtes additionnels
                 $headers[] = 'To: ' . $to_email;
                 $headers[] = 'From: mercator@localhost';
                 // $headers[] = 'Cc: anniversaire_archive@example.com';
                 // $headers[] = 'Bcc: anniversaire_verif@example.com';                
                mail($to_email, $subject, $message, implode("\r\n", $headers));
                }
            })
        // Run the task every week on Monday at 8:00
        // ->weeklyOn(1, '8:00');;
        ->daily();
        */

        $this->info('message sent !');
    }
}
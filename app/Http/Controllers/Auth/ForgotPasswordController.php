<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ForgotPasswordController extends Controller
{
    /**
     * Show the reset password form
     *
     * @return View
     */
    public function showForgetPasswordForm(): View
    {
        return view('auth.passwords.email');
    }

    /**
     * Generated a reset token
     *
     * @return RedirectResponse
     */
    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // check users exists
        if (! DB::table('users')->whereNull('deleted_at')->where('email', $request->email)->exists()) {
            // Log incident
            Log::warning('Unknown reset email used : '.$request->email);

            // return with same message
            return back()->with('message', 'We have e-mailed your password reset link!');
        }

        // Limit number of mail sent per minute (nospam)
        $previousTokenCreationDate = DB::table('password_resets')
            ->select('created_at')
            ->where('email', $request->email)
            ->first();

        if ($previousTokenCreationDate !== null) {
            // Max one mail per 15 minutes
            if (Carbon::now()->diffInMinutes($previousTokenCreationDate->created_at) < 15) {
                // Log incident
                Log::warning('Request reset email already sent to : '.$request->email);

                // return with same message
                return back()->with('message', 'We have e-mailed your password reset link!');
            }
        }

        // Generate random token
        $token = Str::random(64);

        // Delete previous reset token
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        // Send Mail
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host'); // env('MAIL_HOST');
            $mail->SMTPAuth = config('mail.mailers.smtp.username') !== null;
            $mail->Username = config('mail.mailers.smtp.username'); // env('MAIL_USERNAME');
            $mail->Password = config('mail.mailers.smtp.password'); // env('MAIL_PASSWORD');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption'); // env('MAIL_SMTP_SECURE', false) ?: false; // 'tls' | 'ssl' | false
            $mail->SMTPAutoTLS = true; // filter_var(env('MAIL_SMTP_AUTO_TLS', true), FILTER_VALIDATE_BOOLEAN);
            $mail->Port = (int) config('mail.mailers.smtp.port'); // (int) env('MAIL_PORT', 587);

            // Recipients
            $mail->setFrom(config('mail.from.address'));
            $mail->addAddress($request->email);

            // Content
            $mail->isHTML(true);                            // Set email format to HTML
            $mail->Subject = 'Mercator - Reset password';
            $mail->Body =
                '<html><body>'.
                '<h1>Forget Password Email</h1>'.
                'You can reset your password from the link:'.
                "<a href='".route('reset.password.get', $token)."'>Reset Password</a>".
                '</body></html>';

            // DKIM (optionnel)
            $mail->DKIM_domain = config('mail.dkim.domain'); // env('MAIL_DKIM_DOMAIN');
            $mail->DKIM_private = config('mail.dkim.private'); //  env('MAIL_DKIM_PRIVATE');
            $mail->DKIM_selector = config('mail.dkim.selector'); // env('MAIL_DKIM_SELECTOR');
            $mail->DKIM_passphrase = config('mail.dkim.passphrase'); // env('MAIL_DKIM_PASSPHRASE');
            $mail->DKIM_identity = $mail->From;

            // Send email
            $mail->send();

            Log::info("Message has been sent to {$request->email}");
        } catch (Exception $e) {
            Log::error("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");

            return back()->withErrors(['mail' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }

        // Save token in reset_password table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    /**
     * Show reset password form
     *
     * @return View
     */
    public function showResetPasswordForm($token): View
    {
        // TODO: Get token expiration

        // Show reset token form
        return view('auth.passwords.reset', ['token' => $token]);
    }

    /**
     * Reset the password
     *
     * @return RedirectResponse
     */
    public function submitResetPasswordForm(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Check token from table
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (! $updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        // Update password
        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Remove token from table
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        // Redirect
        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}

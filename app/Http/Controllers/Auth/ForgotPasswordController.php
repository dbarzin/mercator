<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the reset password form
     *
     * @return response()
     */
    public function showForgetPasswordForm(): View
    {

        return view('auth.passwords.email');
    }

    /**
     * Generated a reset token
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // check users exists
        if (! DB::table('users')->whereNull('deleted_at')->where('email', $request->email)->exists()) {
            // Log incident
            Log::warning('Unknown reset email used : ' . $request->email);
            // return with same message
            return back()->with('message', 'We have e-mailed your password reset link!');
        }

        // Limit number of mail sent per minute (nospam)
        $previousTokenCreationDate = DB::table('password_resets')
            ->select("created_at")
            ->where("email",$request->email)
            ->first();

        if ($previousTokenCreationDate!=null) {
            // Max one mail per 15 minutes
            if (Carbon::now()->diffInMinutes($previousTokenCreationDate->created_at)<15) {
                // Log incident
                Log::warning('Request reset email already sent to : ' . $request->email);
                // return with same message
                return back()->with('message', 'We have e-mailed your password reset link!');
            }
        }

        // Generate random token
        $token = Str::random(64);

        // Delete previous reset token
        DB::table('password_resets')
            ->where('email',$request->email)
            ->delete();

        // Save token in reset_passord table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Send Mail
        /*
          Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });
        */
        $this->sendMail(
            config('mercator-config.cve.mail-from'),
            $request->email,
            'Forget Password',
            '<html><body>' .
            '<h1>Forget Password Email</h1>' .
            'You can reset your password from the link:' .
            "<a href='" . route('reset.password.get', $token) . "'>Reset Password</a>" .
            '</body></html>'
        );

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    /**
     * Show reset password form
     *
     * @return response()
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
     * @return response()
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

    private function sendMail($mail_from, $mail_to, $subject, $message)
    {
        // set mail header
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html;charset=iso-8859-1',
            'From: '. $mail_from,
        ];

        // Send mail
        if (mail($mail_to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, implode("\r\n", $headers), ' -f'. $mail_from)) {
            Log::warning('Reset password mail sent to '. $mail_to);
        } else {
            Log::warning('Reset password mail sending fail.');
        }
    }
}

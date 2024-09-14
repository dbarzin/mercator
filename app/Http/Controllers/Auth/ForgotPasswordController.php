<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\User;
use Mail;
use Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm(): View
      {
         return view('auth.passwords.email');
      }

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordForm(Request $request): RedirectResponse
      {
          $request->validate([
              'email' => 'required|email',
          ]);

          // check users exists
          if (!DB::table('users')->whereNull("deleted_at")->where('email',$request->email)->exists()) {
            // Log incident
            Log::warning('Unknown reset email used : ' . $request->email);
            // return with stupid message
            return back()->with('message', 'We have e-mailed your password reset link!');
            }

          // Generate random token
          $token = Str::random(64);

          // Save token in reset_passord table
          DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
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
              "<html><body>" .
              "<h1>Forget Password Email</h1>" .
              "You can reset your password from the link:" .
              "<a href='" . route('reset.password.get', $token) . "'>Reset Password</a>" .
              "</body></html>");

          return back()->with('message', 'We have e-mailed your password reset link!');
      }

      private function sendMail($mail_from, $mail_to, $subject, $message) {

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

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token): View
      {
         return view('auth.passwords.reset', ['token' => $token]);
      }

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request): RedirectResponse
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);

          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email,
                                'token' => $request->token
                              ])
                              ->first();

          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }

          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);

          DB::table('password_resets')->where(['email'=> $request->email])->delete();

          return redirect('/login')->with('message', 'Your password has been changed!');
      }
}

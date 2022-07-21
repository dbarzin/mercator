<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function credentials(Request $request)
    {
        return [
            'mail' => $request->email,
            'password' => $request->password,
            'fallback' => [
                'email' => $request->email,
                'password' => $request->password,
            ],
        ];
    }

    public function login(Request $request)
    {
        error_log('LOGIN');
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        // if (! auth()->attempt($loginData)) {
        if (!auth()->attempt($this->credentials($request))) {
            return response(['message' => 'This user does not exist, check your details'], 400);
        }
        error_log('LOGIN - User: '. json_encode(auth()->user()));

        $accessToken = auth()->user()->createToken(auth()->user()->email.' authToken '.now())->accessToken;
        error_log('LOGIN - Token: '.$accessToken);

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}

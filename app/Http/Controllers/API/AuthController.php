<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function credentials(Request $request)
    {
        return [
            'login' => $request->login,
            'password' => $request->password,
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        if (! auth()->attempt($this->credentials($request))) {
            return response(['message' => 'This user does not exist, check your details'], 400);
        }

        $accessToken = auth()->user()->createToken(auth()->user()->login.' authToken '.now())->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
        public function register(Request $request) {
            error_log("REGISTER");
            $validatedData = $request->validate([
               "name" => "required|max:55",
                "email" => "email|required|unique:users",
                "password" => "required|confirmed"
            ]);
            error_log("REGISTER - validated");
            $validatedData["password"] = bcrypt($request->password);

            $user = User::create($validatedData);

            $accessToken = $user->createToken("authToken")->accessToken;
            error_log("REGISTER CALLED Token: ".$accessToken);
            return response(["message" => "you are now registered", "user" => $user, "access_token" => $accessToken], 201);
        }
    */
    public function login(Request $request)
    {
        error_log('LOGIN');
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (! auth()->attempt($loginData)) {
            return response(['message' => 'This user does not exist, check your details'], 400);
        }
        error_log('LOGIN - User: '. json_encode(auth()->user()));

        $accessToken = auth()->user()->createToken(auth()->user()->email.' authToken '.now())->accessToken;
        error_log('LOGIN - Token: '.$accessToken);

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}

/*
class AuthController extends Controller
{
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $access_token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token_type'=> 'Bearer', 'access_token' => $access_token];

                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }
}
*/

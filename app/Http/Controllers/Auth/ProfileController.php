<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile(User $user)
    {
        return view('admin.users.profile', compact('user'));
    }    

    public function saveProfile(Request $request)
    {
        // dd($request->granularity);
        $user = Auth::guard()->user();
        // dd($user);
        $user->granularity = $request->granularity;
        $user->language = $request->language;

        $user->save();
               
        // dd($request);
        return redirect('/profile/preferences');
    }    

}

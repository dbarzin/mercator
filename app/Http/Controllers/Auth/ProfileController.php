<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile(User $user)
    {
        return view('admin.users.profile', compact('user'));
    }

    public function saveProfile(Request $request)
    {
        $user = Auth::guard()->user();

        $user->granularity = $request->granularity;
        $user->language = $request->language;

        $user->save();

        return redirect('/profile/preferences');
    }
}

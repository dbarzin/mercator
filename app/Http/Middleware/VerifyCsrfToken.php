<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Auth;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

/*
=======

>>>>>>> 67938b5237ca1d83f5e1c796c7b21827255316e2
    public function handle($request, Closure $next)
    {
        if(!Auth::check() && $request->route()->named('logout')) {

            $this->except[] = route('logout');

        }

        return parent::handle($request, $next);
    }
<<<<<<< HEAD
 */
}

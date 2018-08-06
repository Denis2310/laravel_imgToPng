<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AdminMiddleware
{

    //Provjerava da li je korisnik administrator, ako nije vraća ga nazad
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if(!$user->isAdmin())
        {
            return redirect()->back();
        }
        return $next($request);
    }
}


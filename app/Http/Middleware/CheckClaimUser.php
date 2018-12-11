<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckClaimUser
{
    public function handle($request, Closure $next)
    {
        if(Auth::user()->user_type == '1' || Auth::user()->user_type == '6'){
            return $next($request);
		}
		else{
			return redirect('/');
		}
    }
}

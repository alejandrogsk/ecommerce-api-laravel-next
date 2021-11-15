<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //get data form the token
        $user = auth()->user();
        $role  = $user->role->name;

        //if is a normal user don't let them pass
        if($role === 'user'){
            return response()->json(['ok'=> false, 'message'=> 'Sorry, you are not an admin', 'role'=> $role]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUser
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
        
        $request->user_id = $user->id;

        $role  = $user->role->name;

        //if is am admin user don't let them pass
        if($role === 'admin'){
            return response()->json(['ok'=> false, 'message'=> 'Sorry, you do not have access', 'role'=> $role]);
        }

        return $next($request);
    }
}

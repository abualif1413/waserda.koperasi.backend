<?php

namespace App\Http\Middleware;

use Closure;
use App\Member;

class WaserdaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $member = Member::where('kode_aktivasi', $token)->get();
        if(count($member) == 0) {
            return \response(['success' => 0, 'message' => 'Unauthorized user'], 401);
        }

        return $next($request);
    }
}

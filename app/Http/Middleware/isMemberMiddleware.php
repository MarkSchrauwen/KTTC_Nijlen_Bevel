<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;

class isMemberMiddleware
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
        $member = Member::where('user_id',"=",auth()->user()->id)->first();
        if(!auth()->check() || is_null($member)) {
            abort(403);
        }
        return $next($request);
    }
}

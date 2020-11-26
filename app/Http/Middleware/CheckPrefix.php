<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;

use Closure;

class CheckPrefix
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
        $params = $request->route()->parameters();
        if($params['prefix'] != auth()->user()->prefix) {
            return abort(404);
        }
        $request->route()->forgetParameter('prefix');
        return $next($request);
    }
}

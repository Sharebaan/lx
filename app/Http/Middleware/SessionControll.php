<?php

namespace App\Http\Middleware;

use Closure;

class SessionControll
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
      if(session()->has('extracomponents') || session()->has('formdata')){
        session()->forget('extracomponents');
        session()->forget('formdata');
      }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */


    public function handle($request, Closure $next, ...$guards)
    {

        if ($request->user() && $request->user()->force_update_password) {
            return redirect( route('password.change') );
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function redirectTo($request)
    {
        return route('login');
    }
}

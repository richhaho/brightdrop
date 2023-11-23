<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Passing array support was taken out so custom implementation of array by adding | in the elements
        $role = explode('|', $role);

        if (! $request->user()->checkRoles($role)) {
            return redirect()->to('home')->with('message', 'Success');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_customer) {
            return $next($request);
        }

        if (Auth::check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        return redirect('/landing-page')->with('error', 'Unauthorized access.');
    }
}

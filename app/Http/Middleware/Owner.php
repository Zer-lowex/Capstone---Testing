<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is a owner
        if (Auth::check() && Auth::user()->usertype === 'Owner') {
            return $next($request);
        }

        if (Auth::check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}

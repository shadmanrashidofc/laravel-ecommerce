<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please login to access admin area.');
        }

        if ($request->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}

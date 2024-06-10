<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
        public function handle(Request $request, Closure $next, $role)
            {
            if ($request->user()->role_id != $role) {
            $url = $request->url();
            return back()
            ->with('error', "Access denied to {$url}");

            }
            return $next($request);
            }
}

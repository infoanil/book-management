<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCandidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (empty($token)) {
            abort(401);
        }

           // Store the token in the session
        $token = trim(str_replace('Bearer ', '', $token));
        session(['candidateToken' => $token]);

        return $next($request);
    }
}

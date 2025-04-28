<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsContestPublished
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->contest->isPublished() && ($request->user() && !$request->user()->isEvaluatorOfTheContest($request->contest))) {
            return response()->json(['message' => 'La competencia no está publicada.'], 403);
        }

        return $next($request);
    }
}

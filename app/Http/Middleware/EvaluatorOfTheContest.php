<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EvaluatorOfTheContest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->isEvaluatorOfTheContest($request->contest)) {
            return response()->json(['message' => 'No eres el evaluador de la competencia.'], 403);
        }

        return $next($request);
    }
}

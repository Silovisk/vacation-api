<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('sanctum')->check()) {

            Log::warning('Attempt to access unauthorized endpoint.', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'message' => 'Unauthorized access. Please authenticate.',
                'error' => true
            ], 401);
        }

        return $next($request);
    }
}

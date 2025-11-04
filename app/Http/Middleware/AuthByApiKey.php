<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthByApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('x-api-key') !== 'test') {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }
        // If the API key is valid, bind the user or perform any necessary authentication logic here.
        $user = User::first();
        auth()->login($user);

        return $next($request);
    }
}

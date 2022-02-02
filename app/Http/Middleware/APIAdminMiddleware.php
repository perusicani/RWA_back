<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class APIAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // first check if user is authenticated
        if (Auth::check()) {
            if (auth()->user()->tokenCan('server:admin')) {
                
                return $next($request);
                
            } else {
                
                $response = [
                    'message' => 'Access denied! You are not an admin.',
                ];
                return response($response, 403);
                
            }

        } else {

            $response = [
                'message' => 'Unauthenticated! Please login first.',
            ];
            return response($response, 401);
            
        }
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckReferer
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
       
        $allowedDomain = 'http://127.0.0.1:8000'; // Replace with your allowed domain

        $referer = $request->headers->get('referer');
        $origin = $request->headers->get('origin');
        if (($referer && strpos($referer, $allowedDomain) === 0) || ($origin && $origin === $allowedDomain)) {
            return $next($request);
        }
        return $this->returnError('Access Denied',403);
       
    }
    public function returnError($errors = false, $code)
    {
        return response([
            'success' => false,
            'message' => 'Error',
            'error' => $errors
        ], $code);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class JWTExceptionHandler
{
    public function handle($request, Closure $next)
    {
        try {
            // Check if token is present in the request
            $token = JWTAuth::parseToken();

            if (!$token->getToken()) {
                return $this->returnError("Authorization Token not found", 401);
            }

            // Authenticate the user using the parsed token
            $user = $token->authenticate();

            if (!$user) {
                Log::error('User not authenticated');
                return $this->returnError("User not authenticated", 401);
            }
        } catch (TokenExpiredException $e) {
            Log::error('Token is Expired: ' . $e->getMessage());
            return $this->returnError("Token is Expired", 403);
        } catch (TokenInvalidException $e) {
            Log::error('Token is Invalid: ' . $e->getMessage());
            return $this->returnError("Token is Invalid", 401);
        } catch (JWTException $e) {
            Log::error('JWT Exception: ' . $e->getMessage());
            return $this->returnError("JWT Exception", 401);
        } catch (\Exception $e) {
            Log::error('Exception caught: ' . $e->getMessage());
            return $this->returnError("Exception caught", 500);
        }

        return $next($request);
    }

    public function returnError($errors = false, $code)
    {
        return response()->json([
            'success' => false,
            'message' => 'Error',
            'error' => $errors
        ], $code);
    }
}

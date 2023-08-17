<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class SecretKeyMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if secret key is already generated
        if (!Cache::has('secret_key')) {

            // Generate new secret key
            $secret_key = Str::random(32);

            // Store the secret key in cache for 1 hour
            Cache::put('secret_key', $secret_key, 60*60);
        }

        return $next($request);
    }
}


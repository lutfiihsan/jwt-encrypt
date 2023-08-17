<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Nullix\CryptoJsAes\CryptoJsAes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function get_secret_key()
    {
        return response()->json(['secret_key' => Cache::get('secret_key')]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        // Decrypt encrypted username and password using secret key
        $secret_key = Cache::get('secret_key');
        $decrypted_username = CryptoJsAes::decrypt($credentials['username'], $secret_key);
        $decrypted_password = CryptoJsAes::decrypt($credentials['password'], $secret_key);

        if(!$token = JWTAuth::attempt(['username' => $decrypted_username, 'password' => $decrypted_password])) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        return response()->json(['access_token' => $token]);
    }
}

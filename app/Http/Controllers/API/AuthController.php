<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

final class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'statusCode' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'invalid password',
                    'data' => null
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            Log::error($e->getMessage());

            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => 'Could not create token',
                'data' => null
            ], $e->getStatusCode());
        }

        return response()->json(compact('token'));
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}

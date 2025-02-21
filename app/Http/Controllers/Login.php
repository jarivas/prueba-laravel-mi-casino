<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login as LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Login extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Not Found.'], 404);
        }

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials, false)) {
            return response()->json(['message' => 'Unauthorized.'], 400);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;
        $seconds = intval(config('sanctum.expiration'));
        $expiresAt = now()->addSeconds($seconds);

        return response()->json(['token' => $token, 'expiresAt' => $expiresAt->format('Y-m-d H:i:s')]);
    }
}

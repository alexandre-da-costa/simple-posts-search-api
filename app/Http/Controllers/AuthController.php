<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\GenerateTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $authService)
    {
        return response()->json(
            $authService->registerUser($request->validated()),
            Response::HTTP_CREATED);
    }

    public function generateToken(GenerateTokenRequest $request, AuthService $authService)
    {
        $authService->validateCredentialsOrFail($request->email, $request->password);

        $token = $authService->createAccessTokenByEmailOrFail(
            $request->email,
            $request->device_name,
            $request->remember_me
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ], Response::HTTP_CREATED);
    }

    public function revokeToken()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class AuthService
{
    public function __construct(private User $user)
    {
    }

    public function registerUser(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return User::create($attributes);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function setUserByEmailOrFail(string $email): void
    {
        if (!$this->user?->email == $email)
            $this->user = $this->getUserByEmail($email) ?: throw new InvalidCredentialsException();
    }

    public function createAccessTokenByEmailOrFail(string $email, string $deviceName, bool $rememberMe): NewAccessToken
    {
        $this->setUserByEmailOrFail($email);
        return $this->user->createToken(
            name: $deviceName,
            expiresAt: $this->getAccessTokenExpirationDate($rememberMe)
        );
    }

    public function validateCredentialsOrFail(string $email, string $password): void
    {
        $this->setUserByEmailOrFail($email);
        $this->user->checkPassword($password) ?: throw new InvalidCredentialsException();
    }

    private function getAccessTokenExpirationDate($rememberMe): ?\DateTime
    {
        return $rememberMe ? null : now()->addMinutes(config('sanctum.expiration'));
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use App\Models\BlacklistToken;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function findUserByRefreshToken(string $refreshToken)
    {
        return User::where('refresh_token', $refreshToken)->first();
    }

    public function blacklistToken(string $token, string $type)
    {
        return BlacklistToken::create([
            'token' => $token,
            'type' => $type,
            'revoked_at' => now(),
        ]);
    }

    public function findUserById(int $userId)
    {
        return User::find($userId);
    }

    public function findUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findUserByCredentials(array $credentials)
    {
        // Vérifier si l'email est fourni et chercher par email
        if (isset($credentials['email']) && isset($credentials['password'])) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                return $user;
            }
            return null;
        }

        // Vérifier si le téléphone est fourni et chercher par téléphone
        if (isset($credentials['phone']) && isset($credentials['secret_code'])) {
            $client = User::where('phone', $credentials['phone'])->first();
            if ($client && Hash::check($credentials['secret_code'], $client->secret_code)) {
                return $client;
            }
            return null;
        }

        return null;
    }
}

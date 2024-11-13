<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function findUserByRefreshToken(string $refreshToken);
    public function findUserById(int $userId);
    public function findUserByEmail(string $email);
    public function blacklistToken(string $token, string $type);
    public function findUserByCredentials(array $credentials);
}

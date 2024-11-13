<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function loginClient(array $credentials);
    public function verifySmsCode($phone, $code);
    public function loginDistributeur(array $credentials);
    public function updateFirstLoginCredentials(array $data);
    public function refresh(string $refreshToken);
    public function logout(string $token);
}
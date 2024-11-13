<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    public function register(array $data);

    public function findByPhone(string $phone);

    public function updateProfile(int $userId, array $data);
}

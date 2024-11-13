<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Facades\{
    UserFacade,
    AccountFacade
};
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function register(array $data)
    {
        // Créer l'utilisateur
        $userData = array_merge($data, [
            'password' => Hash::make($data['password']),
            'secret_code' => Hash::make($data['secret_code'])
        ]);
        $user = UserFacade::create($userData);
        
        // Créer automatiquement un compte pour l'utilisateur
        AccountFacade::create([
            'user_id' => $user->id,
            'balance' => 0,
            'limit' => '200000'
        ]);
        
        return $user;
    }

    public function findByPhone(string $phone)
    {
        return UserFacade::findByPhone($phone);
    }

    public function updateProfile(int $userId, array $data)
    {
        return UserFacade::update($userId, $data);
    }
}

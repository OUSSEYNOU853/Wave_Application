<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        $user = User::create($data);
        return $user;    
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function findByPhone($phone)
    {
        return User::where('phone', $phone)->first();
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function isDistributor($userId)
    {
        $user = $this->find($userId);
        return $user->role == 'distributeur';
    }

    public function isClient($userId)
    {
        $user = $this->find($userId);
        return $user->role == 'client';
    }

    public function findByRole($role)
    {
        return User::where('role', $role)->get();
    }
}
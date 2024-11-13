<?php

namespace App\Interfaces;

interface AccountRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateBalance($id, $amount);
    public function updateLimit($userId, $amount);
    public function findByUserId($userId);
}
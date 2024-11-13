<?php

namespace App\Interfaces;

interface AccountActionRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getDistributorActions($distributorId);
    public function getClientActions($clientId);
}
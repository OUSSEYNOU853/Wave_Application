<?php

namespace App\Interfaces;

interface TransferRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getUserTransfers($userId);
    public function getPendingTransfers();
    public function getScheduledTransfers();
}
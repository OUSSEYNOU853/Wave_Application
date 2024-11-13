<?php

namespace App\Repositories;

use App\Models\ScheduledTransfer;
use App\Interfaces\ScheduledTransferRepositoryInterface;

class ScheduledTransferRepository implements ScheduledTransferRepositoryInterface
{
    public function all()
    {
        return ScheduledTransfer::all();
    }

    public function find($id)
    {
        return ScheduledTransfer::find($id);
    }

    public function create(array $data)
    {
        return ScheduledTransfer::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return ScheduledTransfer::destroy($id);
    }

    public function getActiveTransfers()
    {
        return ScheduledTransfer::where('is_active', true)->get();
    }

    // en francais pour la traduction
    // cette methode permet de recuperer les transfers planifies pour un utilisateur donne
    public function getUserScheduledTransfers($userId)
    {
        return ScheduledTransfer::where('user_id', $userId)->get();
    }
}
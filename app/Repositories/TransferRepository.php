<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transfer;
use App\Interfaces\TransferRepositoryInterface;

class TransferRepository implements TransferRepositoryInterface
{
    public function all()
    {
        return Transfer::all();
    }

    public function find($id)
    {
        return Transfer::find($id);
    }

    public function create(array $data)
    {
        return Transfer::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return Transfer::destroy($id);
    }

    public function getUserTransfers($userId)
    {
        return Transfer::where('sender_id', $userId)->get();
    }

    public function getPendingTransfers()
    {
        return Transfer::where('status', 'EN_ATTENTE')->get();
    }

    public function getScheduledTransfers()
    {
        return Transfer::whereNotNull('scheduled_for')->get();
    }
}

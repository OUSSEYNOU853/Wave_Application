<?php

namespace App\Repositories;

use App\Models\TransferRecipient;
use App\Interfaces\TransferRecipientRepositoryInterface;

class TransferRecipientRepository implements TransferRecipientRepositoryInterface
{
    public function all()
    {
        return TransferRecipient::all();
    }

    public function find($id)
    {
        return TransferRecipient::find($id);
    }

    public function create(array $data)
    {
        return TransferRecipient::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return TransferRecipient::destroy($id);
    }

    public function getTransferRecipients($transferId)
    {
        return TransferRecipient::where('transfer_id', $transferId)->get();
    }
}
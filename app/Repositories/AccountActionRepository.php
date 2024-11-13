<?php

namespace App\Repositories;

use App\Models\AccountAction;
use App\Interfaces\AccountActionRepositoryInterface;

class AccountActionRepository implements AccountActionRepositoryInterface
{
    public function all()
    {
        return AccountAction::all();
    }

    public function find($id)
    {
        return AccountAction::find($id);
    }

    public function create(array $data)
    {
        return AccountAction::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }


    public function delete($id)
    {
        return AccountAction::destroy($id);
    }

    public function getDistributorActions($distributorId)
    {
        return AccountAction::where('distributor_id', $distributorId)->get();
    }

    public function getClientActions($clientId)
    {
        return AccountAction::where('client_id', $clientId)->get();
    }
}

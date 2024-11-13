<?php

namespace App\Repositories;

use App\Models\Account;
use App\Interfaces\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    public function all()
    {
        return Account::all();
    }

    public function find($id)
    {
        return Account::find($id);
    }

    public function create(array $data)
    {
        return Account::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return Account::destroy($id);
    }

    public function updateBalance($id, $amount)
    {
        $account = $this->find($id);
        $account->balance = $amount;
        return $account->save();
    }

    public function updateLimit($userId, $amount)
    {
        $account = $this->findByUserId($userId);
        $account->limit = $amount;
        return $account->save();
    }

    public function findByUserId($userId)
    {
        return Account::where('user_id', $userId)->first();
    }
}

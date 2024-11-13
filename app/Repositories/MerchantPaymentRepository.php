<?php

namespace App\Repositories;

use App\Models\MerchantPayment;
use App\Interfaces\MerchantPaymentRepositoryInterface;

class MerchantPaymentRepository implements MerchantPaymentRepositoryInterface
{
    public function all()
    {
        return MerchantPayment::all();
    }

    public function find($id)
    {
        return MerchantPayment::find($id);
    }

    public function create(array $data)
    {
        return MerchantPayment::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return MerchantPayment::destroy($id);
    }

    public function getMerchantPayments($merchantId)
    {
        return MerchantPayment::where('merchant_id', $merchantId)->get();
    }

    public function getUserPayments($userId)
    {
        return MerchantPayment::where('user_id', $userId)->get();
    }

    public function getPendingPayments()
    {
        return MerchantPayment::where('status', 'EN_ATTENTE')->get();
    }
}